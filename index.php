<?php
date_default_timezone_set("Asia/Kolkata");

// Include the class file
require "Validator.php";

// Data to validate
$data = [
    "name" => "John Doe",
    "age" => 25,
    "email" => "john@example.com",
    "password" => "pass@123",
    "confirm_password" => "pass@123",
    "sex" => "male",
    "phone" => "1236547895",
    "dob" => "1998-07-11"
];

// Create a instance
$v = new Validator($data);

// Run validation
$v->field('name')->required()->alpha([' ']);
$v->field('age') ->required()->numeric()->min_val(14)->max_val(100);
$v->field('email')->required()->email();
$v->field('password')->required()->min_len(8)->max_len(16)->must_contain('@#$&')->must_contain('a-z')->must_contain('A-Z')->must_contain('0-9');
$v->field('confirm_password')->required()->equals($data['password']);
$v->field('sex')->enum(['male', 'female', 'others']);
$v->field('phone')->numeric()->min_len(10)->max_len(10);
$v->field('dob')->date()->date_after('1998-01-01')->date_before('2002-12-31');

// Check if data is valid
if(!$v->is_valid()){
    // Print the error messages
    print_r($v->error_messages);
}