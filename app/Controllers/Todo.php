<?php namespace App\Controllers;

use App\Models\TodoModel;

class Todo extends BaseController
{
    private $todoModel;
    public function __construct(){
        $this->todoModel  = new TodoModel();
    }


	public function index()
	{
		return view('todo/index');
	}

    public function add(){
        $todo = $this->request->getPost('todo');
        
        $result = $this->todoModel->save([
            'todo'          => $todo,
            'created_at'    => date('Y-m-d H:i:s'),
        ]);


        //echo json_encode(['result'=>$result, 'request'=>$this->request]);
        return $this->response->setJSON($result);
    }

    public function getTodos(){
        $todos      = $this->todoModel->asObject()->orderBy('id', 'desc')->findAll();
        return $this->response->setJSON($todos);
    }

    public function delete(){
        $id = $this->request->getPost('id');
        $result = $this->todoModel->delete(['id'=>$id]);
        return $this->response->setJSON($result);
    }

    public function todoCompleteChange(){
        $id     = $this->request->getPost('id');
        $status = $this->request->getPost('status');

        $data = [
            'status' => $status
        ];
        
        $result = $this->todoModel->update($id, $data);
        return $this->response->setJSON($result);
    }
}