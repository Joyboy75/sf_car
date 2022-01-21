<?php

namespace App\Globals;

use App\Repository\GroupeRepository;

class Groupes{

    private $groupeRepository;

    public function __construct(GroupeRepository $groupeRepository)
    {
        $this->groupeRepository = $groupeRepository;
    }
    
    public function getAll(){
        $ggroupes = $this->groupeRepository->findAll();

        return $ggroupes;
    }
}