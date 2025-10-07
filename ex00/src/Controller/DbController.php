<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DbController extends AbstractController 
{
    #[Route('/ex00/', methods: ['GET'])]
    public function index(): Response 
    {
        $html = '
        <!DOCTYPE html>
        <html>
        <head>
            <title>Database Manager</title>
        </head>
        <body>
            <h1>Database Manager</h1>
            <form method="POST" action="/ex00/">
                <button type="submit">Create Database</button>
            </form>
        </body>
        </html>';
        
        return new Response($html);
    }

    #[Route('/ex00/', methods: ['POST'])]
    public function create(): Response
    {
        $message = '';
        $success = false;
        
        try {
            // Create SQLite database connection
            $pdo = new \PDO('sqlite:database.db');

            // If there is an error, throw a PDOException
            $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            
            // Placeholder query - you can edit this later both in upper and lower case works e.g create table === CREATE TABLE
            $query = "CREATE TABLE IF NOT EXISTS my_table (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                username CHAR UNIQUE,
                name CHAR,
                email CHAR UNIQUE,
                enabled BOOLEAN,
                birthdate DATETIME,
                address TEXT
            )";
            
            $pdo->exec($query);
            $message = 'Database created successfully!';
            $success = true;
            
        } catch (\PDOException $e) {
            $message = 'Database creation failed: ' . $e->getMessage();
            $success = false;
        }
        
        $statusClass = $success ? 'success' : 'error';
        $html = '
        <!DOCTYPE html>
        <html>
        <head>
            <title>Database Manager</title>
            <style>
                .success { color: green; }
                .error { color: red; }
            </style>
        </head>
        <body>
            <h1>Database Creation Result</h1>
            <p class="' . $statusClass . '">' . htmlspecialchars($message) . '</p>
            <a href="/ex00/">Back to Manager</a>
        </body>
        </html>';
        
        return new Response($html);
    }

}