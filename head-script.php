<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<link href="css/style.css?v=<?php echo @filemtime(__DIR__ . '/../css/style.css') ?: time(); ?>" rel="stylesheet">
<link href="css/theme.css?v=<?php echo time(); ?>" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.3/font/bootstrap-icons.min.css" />

<style>
    /* CSS-only mobile menu (shared across every page) */
    .menu-toggle-checkbox 
    {
        display: none;
    }

    .menu-toggle-label 
    {
        cursor: pointer;
    }

    #side-menu 
    {
        left: -250px;
        transition: left 0.3s ease;
    }

    #overlay 
    {
        display: none;
    }

    .menu-toggle-checkbox:checked~#side-menu 
    {
        left: 0;
    }

    .menu-toggle-checkbox:checked~#overlay 
    {
        display: block;
    }

    .overlay-label 
    {
        display: block;
        width: 100%;
        height: 100%;
    }
</style>