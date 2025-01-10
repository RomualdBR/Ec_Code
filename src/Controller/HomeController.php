<?php

namespace App\Controller;

use App\Repository\BookReadRepository;
use App\Repository\BookRepository;
use App\Entity\BookRead;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;


class HomeController extends AbstractController
{
    private BookReadRepository $bookReadRepository;
    private BookRepository $bookRepository;
    private EntityManagerInterface $entityManager;

    // Inject the repository via the constructor
    public function __construct(BookReadRepository $bookReadRepository, BookRepository $bookRepository, EntityManagerInterface $entityManager)
    {
        $this->bookReadRepository = $bookReadRepository;
        $this->bookRepository = $bookRepository;
        $this->entityManager = $entityManager;
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
        $booksRead = $this->bookReadRepository->findByUserId($userId, false);

        foreach ($booksRead as $bookread) {
            $books = $this->bookRepository->find($bookread->getBookId());
            if ($books) { {
                    $book[$bookread->getBookId()] = $books;
                }
            }
        }

        // Render the 'hello.html.twig' template
        return $this->render('pages/home.html.twig', [
            'allbooks' => $allbooks,
            'books' => $book,
            'booksRead' => $booksRead,
            'name' => 'Accueil', // Pass data to the view
        ]);
    }

    #[Route('/add-book-read', name: 'app.add_book_read', methods: ['POST'])] 
    public function addBookRead(Request $request): Response
    {
        $user = $this->getUser();
        if (!$user instanceof User) {
            return $this->redirectToRoute('auth.login');
        }
        $bookRead = new BookRead();
        $bookRead->setUserId($user->getId());
        $bookRead->setBookId($request->request->get('book_id'));
        $bookRead->setRating($request->request->get('rating'));
        $bookRead->setDescription($request->request->get('description'));
        $bookRead->setRead($request->request->get('is_read') ? true : false);
        $bookRead->setCreatedAt(new \DateTime());
        $bookRead->setUpdatedAt(new \DateTime());
        $this->entityManager->persist($bookRead);
        $this->entityManager->flush();
        return $this->redirectToRoute('app.home');
    }
}
