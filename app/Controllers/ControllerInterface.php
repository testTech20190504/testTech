<?php

namespace App\Controllers;

interface ControllerInterface
{
    /**
     * Methode pour page d'accueil
     */
    public function index();

    /**
     * Methode pour page de creation
     */
    public function add();

    /**
     * Methode pour page de modification
     * @param mixed $parameter
     */
    public function edit(int $parameter);

    /**
     * Methode pour page de suppression
     */
    public function delete(int $parameter);

    /**
     * @param array $data
     *
     * @return array
     */
    public function sanitize(array $data = []): array;
}
