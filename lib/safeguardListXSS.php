<?php
if(isset($_GET['con']))
{
    $_GET['con'] = strip_tags(htmlspecialchars($_GET['con']));
}
if(isset($_GET['page']))
{
    $_GET['page'] = strip_tags(htmlspecialchars($_GET['page']));
}
if(isset($_GET['add']))
{
    $_GET['add'] = strip_tags(htmlspecialchars($_GET['add']));
}
if(isset($_POST['name-edit']))
{
    $_POST['name-edit'] = strip_tags(htmlspecialchars($_POST['name-edit']));
}
if(isset($_POST['desc-edit']))
{
    $_POST['desc-edit'] = strip_tags(htmlspecialchars($_POST['desc-edit']));
}
if(isset($_POST['categoryList']))
{
    $_POST['categoryList'] = strip_tags(htmlspecialchars($_POST['categoryList']));
}
if(isset($_POST['submit']))
{
    $_POST['submit'] = strip_tags(htmlspecialchars($_POST['submit']));
}
if(isset($_POST['edit']))
{
    $_POST['edit'] = strip_tags(htmlspecialchars($_POST['edit']));
}
if(isset($_POST['description-edit']))
{
    $_POST['description-edit'] = strip_tags(htmlspecialchars($_POST['description-edit']));
}
if(isset($_POST['delete']))
{
    $_POST['delete'] = strip_tags(htmlspecialchars($_POST['delete']));
}
if(isset($_POST['pass-edit']))
{
    $_POST['pass-edit'] = strip_tags(htmlspecialchars($_POST['pass-edit']));
}
if(isset($_POST['name']))
{
    $_POST['name'] = strip_tags(htmlspecialchars($_POST['name']));
}
if(isset($_POST['description']))
{
    $_POST['description'] = strip_tags(htmlspecialchars($_POST['description']));
}
if(isset($_POST['category']))
{
    $_POST['category'] = strip_tags(htmlspecialchars($_POST['category']));
}
if(isset($_POST['email']))
{
    $_POST['email'] = strip_tags(htmlspecialchars($_POST['email']));
}
if(isset($_POST['login']))
{
    $_POST['login'] = strip_tags(htmlspecialchars($_POST['login']));
}
if(isset($_POST['password']))
{
    $_POST['password'] = strip_tags(htmlspecialchars($_POST['password']));
}

