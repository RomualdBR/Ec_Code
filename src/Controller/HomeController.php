<?php

namespace App\Controller;

use App\Repository\BookReadRepository;
use App\Repository\BookRepository;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


class HomeController extends AbstractController
{
    private BookReadRepository $bookReadRepository;
    private BookRepository $bookRepository;

    // Inject the repository via the constructor
    public function __construct(BookReadRepository $bookReadRepository, BookRepository $bookRepository)
    {
        $this->bookReadRepository = $bookReadRepository;
        $this->bookRepository = $bookRepository;
    }

    #[Route('/', name: 'app.home')]
    public function index(): Response
    {
        $user = $this->getUser();

        // Check if the user is logged in
        if (!$user instanceof User) {
            // Handle the case where the user is not logged in
            return $this->redirectToRoute('auth.login');
        }

        $userId = $user->getId();
        $allbooks = $this->bookRepository->findAll();
        $booksRead  = $this->bookReadRepository->findByUserId($userId-7, false);
        
        foreach ($booksRead as $bookread) {
            $books = $this->bookRepository->find($bookread->getBookId());
            if($books){{
                $book[$bookread->getBookId()] = $books;
            }}
        }

        // Render the 'hello.html.twig' template
        return $this->render('pages/home.html.twig', [
            'allbooks' => $allbooks,
            'books' => $book,
            'booksRead' => $booksRead,
            'name'      => 'Accueil', // Pass data to the view
        ]);
    }
}
