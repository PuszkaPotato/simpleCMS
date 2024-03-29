<?php namespace App\Controllers;

use CodeIgniter\Controller;
use App\Controllers\BaseController;

use App\Models\GalleryModel;
use App\Models\UsersModel;

class Gallery extends BaseController
{
    public function index()
	{
        $usersModel = new UsersModel();
        $galleryModel = new GalleryModel();

        if(!isset($_SESSION['logged_in']))
        {
            return redirect()->to(base_url('/admin/login'));
        }

        $data['settings'] = $this->cfg;

        $data['siteTitle'] = $data['settings']['siteName'].' | Galeria';
        $data['siteDesc'] = "Test";

        $data['albums'] = $galleryModel->orderBy('created_at', 'desc')->findAll();

        echo view('admin/templates/header', $data);
		echo view('admin/pages/gallery', $data);
        echo view('admin/templates/footer', $data);
	}

	public function view($page)
	{
        $usersModel = new UsersModel();
        $galleryModel = new GalleryModel();

        if(!isset($_SESSION['logged_in']))
        {
            return redirect()->to(base_url('/admin/login'));
        }

        $userData = $usersModel->find($_SESSION['userId']);

        if(!is_file(APPPATH.'Views/admin/pages/gallery/'.$page.'.php'))
        {
            //Can't find file
            throw new \CodeIgniter\Exceptions\PageNotFoundException($page);
        }

        if($page === 'edit')
        {
            $data['album'] = $galleryModel->find($this->request->getGet('albumid'));
            $data['album']['date'] = date('m-d-Y', strtotime($data['album']['date']));
        }

        $data['settings'] = $this->cfg;

        $data['siteTitle'] = $this->cfg['siteName'].' | Galeria';
        $data['siteDesc'] = "Test";

        echo view('admin/templates/header', $data);
		echo view('admin/pages/gallery/'.$page, $data);
        echo view('admin/templates/footer', $data);
	}

    //Display portfolio on website
    public function portfolio()
    {
        $model = new GalleryModel(); 

        $data['settings'] = $this->cfg;

        $data['siteTitle'] = $data['settings']['siteName'] . ' - Galeria';
        $data['siteDesc'] = 'Portfolio';
        $data['year'] = date('Y');

        if($this->request->getGet('id'))
        {
            $data['album'] = $model->find($this->request->getGet('id'));

            echo view('templates/header', $data);
            echo view('pages/portfolio-details', $data);
            echo view('templates/footer', $data);
        }
        else 
        {
            $data['albums'] = $model->findAll();

            echo view('templates/header', $data);
            echo view('pages/portfolio', $data);
            echo view('templates/footer', $data);
        }
    }

    public function createAlbum()
    {
        $validation =  \Config\Services::validation();
        $model = new GalleryModel();
        if($this->request->getMethod() === 'post' && $this->validate('createAlbum'))
        {
            //Get files
            if($files = $this->request->getFiles())
            {
                //Create array to temporary store names of files for database
                $images = [];

                foreach($files['file'] as $file){
                    if($file->isValid() && !$file->hasMoved()) {
                        //Generate new file name and move to uploads directory
                        $newName = $file->getRandomName();
                        $file->move(ROOTPATH . 'public/uploads', $newName);

                        //Push new file name to array
                        array_push($images, $newName);
                    }
                }

                $data = $this->request->getPost();

                $data['date'] = date('Y-m-d', strtotime($data['date']));

                $images = serialize($images);
                $data['pictures'] = $images;

                $model->insert($data);

                //To-Do
                //UX stuff
                //Validate data before saving files and sending to database
                //Do above


                
            } else {
                return json_encode(['status' => 'failure', 'csrf' => csrf_hash(), 'message' => 'Nie wysłano żadnych plików!']);
            }

            if($this->request->isAjax())
            {
                $message = 'Pomyślnie utworzono album o tytule ' . $data['title'].'!';
                return $this->response->setJSON(json_encode(['status' => 'success', 'csrf' => csrf_hash(), 'message' => $message]));
            }
            
        } else if($validation->getErrors())
        {
            $errors = $validation->getErrors();

            $this->response->setStatusCode(400);

            return $this->response->setJSON(json_encode(['status'=> 'invalid', 'csrf' => csrf_hash(), 'errors' => $errors, 'message' => "Musisz poprawić błędy w formularzu!"]));
        } else {
            $errors = $validation->getErrors();
            return json_encode(['status'=> 'failure', 'csrf' => csrf_hash(), 'message' => 'Wystąpił nieznany błąd!', 'errors' => $errors]);
        }
    }

    public function editAlbum()
    {
        $validation =  \Config\Services::validation();
        $model = new GalleryModel();
        if($this->request->getMethod() === 'post' && $this->validate('createAlbum'))
        {
            $data = $this->request->getPost();

            $data['date'] = date('Y-m-d', strtotime($data['date']));

            if(!$model->update($data['id'], $data))
            {
                $this->response->setStatusCode(400);

                return $this->response->setJSON(json_encode(['status'=> 'invalid', 'csrf' => csrf_hash(), 'message' => "Wystąpił błąd podczas aktualizacji danych na serwerze!"]));    
            }  

            $files = $this->request->getFiles();
            //Get files
            if($files && $files['file'][0]->getName() !== 'blob')
            { 
                //Create array to temporary store names of files for database
                $images = [];

                foreach($files['file'] as $file){
                    if($file->isValid() && !$file->hasMoved()) {
                        //Generate new file name and move to uploads directory
                        $newName = $file->getRandomName();
                        $file->move(ROOTPATH . 'public/uploads', $newName);

                        //Push new file name to array
                        array_push($images, $newName);
                    }
                }



                if(!$model->updatePictures($images, $data['id']))
                {
                    $this->response->setStatusCode(400);

                    return $this->response->setJSON(json_encode(['status'=> 'invalid', 'csrf' => csrf_hash(), 'message' => "Wystąpił błąd podczas aktualizacji listy plików w bazie danych!"]));        
                }
            }

            if($this->request->isAjax())
            {
                $message = 'Pomyślnie edytowano album '.$data['title'].'!';

                return $this->response->setJSON(json_encode(['status' => 'success', 'csrf' => csrf_hash(), 'message' => $message]));
            }
        } else if($validation->getErrors())
        {
            $errors = $validation->getErrors();

            $this->response->setStatusCode(400);

            return $this->response->setJSON(json_encode(['status'=> 'invalid', 'csrf' => csrf_hash(), 'errors' => $errors, 'message' => "Musisz poprawić błędy w formularzu!"]));
        } else {
            $errors = $validation->getErrors();

            $this->response->setStatusCode(400);

            return json_encode(['status'=> 'failure', 'csrf' => csrf_hash(), 'message' => 'Wystąpił nieznany błąd!', 'errors' => $errors]);
        }
    }

    public function deleteAlbum()
    {
        $model = new GalleryModel();
        if($this->request->getMethod() === 'post')
        {
            $data = $this->request->getJSON();

            $data = (array) $data;

            if(!$model->deleteAlbum($data['id'], true))
            {
                $message = 'Wystąpił błąd podczas usuwania albumu!';

                $this->response->setStatusCode(400);

                return $this->response->setJSON(json_encode(['status'=> 'failure', 'csrf' => csrf_hash(), 'message' => "Wystąpił błąd podczas usuwania albumu!"]));
            }



            if($this->request->isAjax())
            {
                $message = 'Pomyślnie usunięto album!';

                return $this->response->setJSON(json_encode(['status' => 'success', 'csrf' => csrf_hash(), 'message' => $message]));
            }
            
        } else if($validation->getErrors())
        {
            $errors = $validation->getErrors();

            $this->response->setStatusCode(400);

            return $this->response->setJSON(json_encode(['status'=> 'invalid', 'csrf' => csrf_hash(), 'errors' => $errors, 'message' => "Musisz poprawić błędy w formularzu!"]));
        } else {
            $errors = $validation->getErrors();
            return json_encode(['status'=> 'failure', 'csrf' => csrf_hash(), 'message' => 'Wystąpił nieznany błąd!', 'errors' => $errors]);
        }
    }

    public function deletePicture()
    {
        $galleryModel = new GalleryModel();
        
        if($this->request->getMethod() === 'post')
        {
            $data = $this->request->getJSON();

            $data = (array) $data;

            if(file_exists(ROOTPATH.'public/uploads/'.$data['name']))
            {
                if(!unlink(ROOTPATH.'/public/uploads/'.$data['name']))
                {
                    $message = 'Wystąpił błąd podczas usuwania pliku!';
                    $this->response->setStatusCode(400);

                    return $this->response->setJSON(json_encode(['status'=> 'failure', 'csrf' => csrf_hash(), 'message' => $message]));
                }
            }

            $galleryModel->removePicture($data['name'], $data['id']);
            
            if($this->request->isAjax())
            {
                $message = 'Pomyślnie usunięto plik';
                return json_encode(['status' => 'success', 'csrf' => csrf_hash(), 'message' => $message]);
            }                 
        } else {
            return json_encode(['status'=> 'failure', 'csrf' => csrf_hash(), 'message' => 'Wystąpił nieznany błąd!', 'errors' => $errors]);
        }
    }
}
