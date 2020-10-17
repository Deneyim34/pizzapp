<?php

namespace App\Interfaces;

interface IRepository
{
    public function getAll();

    public function getById($id);

    public function getByPage($page, $totalPerPage, $search, $searchWhere);

    public function create(array $data);

    public function update(array $data, $id);

    public function destroy($id);

}
