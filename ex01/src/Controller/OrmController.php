<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class OrmController extends AbstractController 
{
    
    #[Route(path: '/ex01/', methods: ['GET'])]
    public function index(Request $request): Response 
    {
        $message = $request->query->get('message');
        $status = $request->query->get('type', 'info');

        
        $html = '
        <!DOCTYPE html>
        <html>
        <head>
            <title>ORM DB-Table</title>
            <style>
                .container { max-width: 600px; margin: 0 auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
                .msg{padding:.8rem 1rem;border-radius:6px;margin:1rem 0}
                .success{background:#e7f9ee;border:1px solid #b8e6c7}
                .error{background:#ffecec;border:1px solid #ffc7c7}
                .form-container { text-align: center; margin-top: 20px; }
                .btn { background-color: #007bff; color: white; padding: 10px 20px; border: none; border-radius: 4px; font-size: 16px;}
                h1 { color: #333; text-align: center; margin-bottom: 30px; }
            </style>
        </head>
        <body>
            <div class="container">
                <h1>ORM Database Manager</h1>
                <p class="msg ' . $status . '">' . htmlspecialchars($message) . '</p>


            </div>

            <div class="form-container">
                <form method="POST" action="/ex01/">
                    <button type="submit" class="btn">Create/Update Database Schema</button>
                </form>
            </div>
        </body>
        </html>';

        return new Response($html);
    }

    #[Route(path: '/ex01/', methods: ['POST'])]
    public function createTable(EntityManagerInterface $em): Response 
    {
        $tool = new SchemaTool($em);
        $meta = $em->getClassMetadata(User::class);
        $message = '';
        $success = false;
        try {
            $tool->updateSchema([$meta]);
            $message = 'Database schema created/updated successfully for table: ' . $meta->getTableName();
            $success = true;
        } catch (\Exception $e) {
            $message = 'Error executing database command: ' . $e->getMessage();
            $success = false;
        }
        $statusClass = $success ? 'success' : 'error';
        $html = '<!DOCTYPE html>
        <html>
        <head>
            <title>ORM DB-Table</title>
            <style>
                .success { background-color: #d4edda; color: #155724; }
                .error { background-color: #f8d7da; color: #721c24; }
            </style>
        </head>
        <body>
            <div class="container">
                <h1>ORM Database Manager</h1>
                <p class="' . $statusClass . '">' . htmlspecialchars($message) . '</p>
                <a href="/ex01/">Back to Home</a>
            </div>
        </body>
        </html>';
                
        // Redirect back to index with message
        return new Response($html);
    }
}