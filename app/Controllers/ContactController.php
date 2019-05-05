<?php

namespace App\Controllers;

use App\Controllers\ControllerInterface;
use App\Database;
use App\Models\ContactModel as Contact;
use Exception;

class ContactController extends MainController implements ControllerInterface
{
    /** @var int $userId */
    protected $userId;

    /**
     * ContactController constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->userId = $_SESSION['auth']['id'];
    }

    /**
     * Affichage de la liste des contacts de l'utilisateur connecté
     */
    public function index()
    {
        $contacts = [];
        if (!empty($this->userId)) {
            $contacts = $this->Contact->getContactByUser($this->userId);
        }
        echo $this->twig->render('index.html.twig', ['contacts' => $contacts]);
    }

    /**
     * Ajout d'un contact
     */
    public function add()
    {
        $error = false;

        if (!empty($_POST)) {

            try {
                $errors = $this->sanitize($_POST);

                if (!empty($errors)) {
                    throw new Exception('POST data are invalid');
                }

                $response = $this->formatDataForDb($_POST);

                if (!empty($response)) {

                    $db = new Database();
                    $contact = new Contact($db);

                    $contact->create([
                        'nom'    => $response['nom'],
                        'prenom' => $response['prenom'],
                        'email'  => $response['email'],
                        'userId' => 1, //$this->userId
                    ]);

                    header('Location: /contact/index');
                }

            } catch (Exception $e) {
                // silent error
            } catch (\PDOException $e) {
                // silent error
            } finally {
                $error = true;
            }
        }

        echo $this->twig->render('add.html.twig', ['error' => $error, 'errors' => $errors]);
    }

    /**
     * Modification d'un contact
     */
    public function edit()
    {
        //@todo
    }

    /**
     * Suppression d'un contact
     */
    public function delete()
    {
        $result = $this->Contact->delete($_GET['id']);
        if ($result) {
            header('Location: /index.php?p=contact.index');
        }
    }

    /**
     * @param array $data
     * @return array
     */
    public function sanitize(array $data = []): array
    {
        $errors = [];

        if (empty($data['nom'])) {
            $errors[] = 'Le nom est obligatoire';
        }

        if (empty($data['prenom'])) {
            $errors[] = 'Le prenom est obligatoire';
        }

        if (empty($data['email'])) {
            $errors[] = 'Le email est obligatoire';
        } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Le format de l\'email est invalide';
        }

        $palindrome = json_decode($this->apiClient('palindrome', ['name' => $data['nom']]));

        if ($palindrome->response) {
            $errors[] = 'Le nom du contact ne peut pas être un palindrome';
        }

        return $errors;
    }

    /**
     * @param array $data
     * @return array
     */
    public function formatDataForDb(array $data = []): array
    {
        $response = [];

        $prenom = ucfirst(strtolower($data['prenom']));
        $nom    = ucfirst(strtolower($data['nom']));
        $email  = strtolower($data['email']);

        $response = [
            'email'    => $email,
            'prenom'   => $prenom,
            'nom'      => $nom
        ];

        return $response;
    }
}
