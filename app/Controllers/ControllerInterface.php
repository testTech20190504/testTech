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
     */
    public function edit();

    /**
     * Methode pour page de suppression
     */
    public function delete();

    /**
     * @param array $data
     *
     * @return array
     */
    public function sanitize(array $data = []): array;

    /**
     * Methode pour page de creation
     */
    public function create();
}