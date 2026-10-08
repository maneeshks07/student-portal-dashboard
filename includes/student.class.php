<?php

class Student 
    {
        var $conn;
        var $errorMessage;

        function __construct($conn) 
            {
                $this->conn         = $conn;
                $this->errorMessage = "";
            }

        function query($sql, $types = "", $params = array()) 
            {
                $stmt = mysqli_prepare($this->conn, $sql);

                if ($types != "") 
                    {
                        mysqli_stmt_bind_param($stmt, $types, ...$params);
                    }

                mysqli_stmt_execute($stmt);
                return mysqli_stmt_get_result($stmt);
            }

        function execute($sql, $types = "", $params = array()) 
            {
                try 
                    {
                        $stmt = mysqli_prepare($this->conn, $sql);

                        if ($types != "") 
                            {
                                mysqli_stmt_bind_param($stmt, $types, ...$params);
                            }

                        return mysqli_stmt_execute($stmt);
                    } 
                catch (Exception $e) 
                    {
                        return false;
                    }
            }

        function checkPassword($plain, $stored) 
            {
                if (password_get_info($stored)["algo"] !== null) 
                    {
                        return password_verify($plain, $stored);
                    }

                // old accounts that still have a plain-text password
                return hash_equals($stored, $plain);
            }

        function login($username, $password) 
            {
                $result = $this->query("SELECT id, username, password FROM users WHERE username = ?", "s", array($username));
                $row    = $result ? mysqli_fetch_assoc($result) : false;

                if ($row && $this->checkPassword($password, $row["password"])) 
                    {
                        if (password_get_info($row["password"])["algo"] === null) 
                            {
                                $this->execute("UPDATE users SET password = ? WHERE id = ?", "si", array(password_hash($password, PASSWORD_DEFAULT), $row["id"]));
                            }

                        session_regenerate_id(true);
                        $_SESSION["username"] = $row["username"];
                        return true;
                    } 
                else 
                    {
                        $this->errorMessage = "Invalid username or password.";
                        return false;
                    }
            }

        function getCount($sql) 
            {
                $result = mysqli_query($this->conn, $sql);
                $row    = mysqli_fetch_assoc($result);
                return $row["total"];
            }
        
        function usernameExists($username) 
            {
                $result = $this->query("SELECT id FROM users WHERE username = ?", "s", array($username));
                return mysqli_num_rows($result) > 0;
            }

        function usernameTakenByOther($username, $id) 
            {
                $result = $this->query("SELECT id FROM users WHERE username = ? AND id != ?", "si", array($username, $id));
                return $result && mysqli_num_rows($result) > 0;
            }

        function emailTakenByOther($email, $id) 
            {
                $result = $this->query("SELECT id FROM users WHERE email = ? AND id != ?", "si", array($email, $id));
                return $result && mysqli_num_rows($result) > 0;
            }

        function registerStudent($fullname, $username, $email, $password, $phone, $gender, $dob, $age, $hobbies, $qualifications, $about, $file) 
            {
                if ($this->usernameExists($username)) 
                    {
                        $this->errorMessage = "Username already exists.";
                        return false;
                    }

                if ($file["error"] != 0) 
                    {
                        $this->errorMessage = "Error uploading photo.";
                        return false;
                    }

                $fileType = mime_content_type($file["tmp_name"]);

                if (!in_array($fileType, array("image/jpeg", "image/png", "image/webp", "image/gif"))) 
                    {
                        $this->errorMessage = "Profile picture must be JPG, PNG, WEBP or GIF.";
                        return false;
                    }

                if ($file["size"] > 2 * 1024 * 1024) 
                    {
                        $this->errorMessage = "Profile picture must be smaller than 2MB.";
                        return false;
                    }

                $target_dir = __DIR__ . "/../uploads/";

                if (!is_dir($target_dir)) 
                    {
                        mkdir($target_dir, 0755, true);
                    }

                $extension   = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));
                $filename    = "student_" . uniqid() . "." . $extension;
                $target_file = $target_dir . $filename;

                if (!move_uploaded_file($file["tmp_name"], $target_file)) 
                    {
                        $this->errorMessage = "Error uploading photo.";
                        return false;
                    }

                $hobbies_text        = implode(", ", $hobbies);
                $qualification_text  = implode(", ", $qualifications);
                $passwordHash        = password_hash($password, PASSWORD_DEFAULT);

                $sql = "INSERT INTO users (fullname, username, email, phone, gender, hobbies, age, qualifications, password, dob, about, photo) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

                if ($this->execute($sql, "ssssssssssss", array($fullname, $username, $email, $phone, $gender, $hobbies_text, $age, $qualification_text, $passwordHash, $dob, $about, $filename))) 
                    {
                        return true;
                    } 
                else
                    {
                        $this->errorMessage = "Error saving the student.";
                        return false;
                    }
            }

        function getStudentById($id) 
            {
                $result = $this->query("SELECT * FROM users WHERE id = ?", "i", array($id));
                return $result ? mysqli_fetch_assoc($result) : false;
            }

        function getStudentByUsername($username) 
            {
                $result = $this->query("SELECT * FROM users WHERE username = ?", "s", array($username));
                return $result ? mysqli_fetch_assoc($result) : false;
            }

        function searchStudents($fullname, $email, $phone) 
            {
                $where  = "";
                $types  = "";
                $params = array();

                if ($fullname != "") 
                    {
                        $where   .= " and fullname like ?";
                        $types   .= "s";
                        $params[] = "%" . $fullname . "%";
                    }

                if ($email != "") 
                    {
                        $where   .= " and email like ?";
                        $types   .= "s";
                        $params[] = "%" . $email . "%";
                    }

                if ($phone != "") 
                    {
                        $where   .= " and phone like ?";
                        $types   .= "s";
                        $params[] = "%" . $phone . "%";
                    }

                $sql = "SELECT * FROM users where 1=1 " . $where . " ORDER BY id DESC";
                return $this->query($sql, $types, $params);
            }

        function deleteStudent($id, $currentUsername) 
            {
                $student = $this->getStudentById($id);

                if (!$student) 
                    {
                        $this->errorMessage = "Student not found.";
                        return false;
                    }

                if ($student["username"] == $currentUsername) 
                    {
                        $this->errorMessage = "You cannot delete your own account.";
                        return false;
                    }

                if ($student["photo"] != "") 
                    {
                        $file = __DIR__ . "/../uploads/" . $student["photo"];

                        if (file_exists($file)) 
                            {
                                unlink($file);
                            }
                    }

                if ($this->execute("DELETE FROM users WHERE id = ?", "i", array($id))) 
                    {
                        return true;
                    } 
                else 
                    {
                        $this->errorMessage = "Error deleting student.";
                        return false;
                    }
            }

        function updateStudent($id, $fullname, $username, $email, $phone, $gender, $dob, $age, $hobbies, $qualifications, $about, $password, $confirmPassword, $file, $oldPhoto) 
            {
                if ($fullname == "" || $username == "" || $email == "") 
                    {
                        $this->errorMessage = "Full name, username and email are required.";
                        return false;
                    }

                if ($password != "" && strlen($password) < 6) 
                    {
                        $this->errorMessage = "Password must be at least 6 characters.";
                        return false;
                    }

                if ($password != "" && $password != $confirmPassword) 
                    {
                        $this->errorMessage = "Passwords do not match.";
                        return false;
                    }

                if ($this->usernameTakenByOther($username, $id)) 
                    {
                        $this->errorMessage = "That username is already taken.";
                        return false;
                    }

                if ($this->emailTakenByOther($email, $id)) 
                    {
                        $this->errorMessage = "That email is already used by another account.";
                        return false;
                    }

                $photo = $oldPhoto;

                if (isset($file) && $file["error"] == 0) 
                    {
                        $fileType = mime_content_type($file["tmp_name"]);
                        $fileSize = $file["size"];

                        if (!in_array($fileType, array("image/jpeg", "image/png", "image/webp", "image/gif"))) 
                            {
                                $this->errorMessage = "Profile picture must be JPG, PNG, WEBP or GIF.";
                                return false;
                            }

                        if ($fileSize > 2 * 1024 * 1024) 
                            {
                                $this->errorMessage = "Profile picture must be smaller than 2MB.";
                                return false;
                            }

                        $extension = pathinfo($file["name"], PATHINFO_EXTENSION);
                        $photo     = "student_" . uniqid() . "." . $extension;

                        if (!is_dir(__DIR__ . "/../uploads")) 
                            {
                                mkdir(__DIR__ . "/../uploads", 0755, true);
                            }

                        $uploadPath = __DIR__ . "/../uploads/" . $photo;

                        if (move_uploaded_file($file["tmp_name"], $uploadPath)) 
                            {
                            if ($oldPhoto != "" && file_exists(__DIR__ . "/../uploads/" . $oldPhoto)) 
                                {
                                    unlink(__DIR__ . "/../uploads/" . $oldPhoto);
                                }
                            } 
                        else 
                            {
                                $this->errorMessage = "Could not upload the profile picture.";
                                return false;
                            }
                    }

                $hobbies_text       = implode(", ", $hobbies);
                $qualification_text = implode(", ", $qualifications);

                $saved = $this->execute("UPDATE users SET fullname = ?, username = ?, email = ?, phone = ?, gender = ?, dob = ?, age = ?, qualifications = ?, hobbies = ?, about = ?, photo = ? WHERE id = ?", "sssssssssssi", array($fullname, $username, $email, $phone, $gender, $dob, $age, $qualification_text, $hobbies_text, $about, $photo, $id));

                if ($saved && $password != "")  
                    {
                        $saved = $this->execute("UPDATE users SET password = ? WHERE id = ?", "si", array(password_hash($password, PASSWORD_DEFAULT), $id));
                    }

                if ($saved) 
                    {
                        return true;
                    } 
                else  
                    {
                        $this->errorMessage = "Error updating student.";
                        return false;
                    }
            }

        function changePassword($id, $currentPassword, $oldPassword, $newPassword, $confirmPassword) 
            {
                if ($oldPassword == "" || $newPassword == "" || $confirmPassword == "") 
                    {
                        $this->errorMessage = "Please fill in all fields.";
                        return false;
                    }

                if (!$this->checkPassword($oldPassword, $currentPassword)) 
                    {
                        $this->errorMessage = "Old password is incorrect.";
                        return false;
                    }

                if (strlen($newPassword) < 6) 
                    {
                        $this->errorMessage = "New password must be at least 6 characters.";
                        return false;
                    }

                if ($newPassword != $confirmPassword) 
                    {
                        $this->errorMessage = "New passwords do not match.";
                        return false;
                    }

                if ($this->execute("UPDATE users SET password = ? WHERE id = ?", "si", array(password_hash($newPassword, PASSWORD_DEFAULT), $id))) 
                    {
                        return true;
                    } 
                else 
                    {
                        $this->errorMessage = "Error updating password.";
                        return false;
                    }
            }

        function getHeaderInfo($username) 
            {
                $result = $this->query("SELECT fullname, photo FROM users WHERE username = ?", "s", array(trim($username)));
                return $result ? mysqli_fetch_assoc($result) : null;
            }

    }

?>
