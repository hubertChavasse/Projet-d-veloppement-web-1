<?php
    require_once '../vendor/autoload.php';
    use App\Page;  // Inclut la classe Page

$page = new Page();
$page->session->destroy();
header('Location: index.php');