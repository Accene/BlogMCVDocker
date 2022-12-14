<?php

namespace App\Controllers\Blog;

use System\Controller;

class RegisterController extends Controller
{
     /**
     *
     * @return mixed
     */
    public function index()
    {
        if ($loginModel->isLogged()) {
            return $this->url->redirectTo('/');
        }

        $this->blogLayout->title('Create New Account');

        $view = $this->view->render('blog/users/register');

        // disable sidebar
        $this->blogLayout->disable('sidebar');

        return $this->blogLayout->render($view);
    }

    /**
    * creation de new User
    *
    * @return string | json
    */
    public function submit()
    {
        $json = [];

        if ($this->isValid()) {
            $this->request->setPost('users_group_id', 2);
            $this->load->model('Users')->create();

            $json['success'] = 'User Has Been Created Successfully';

            $json['redirectTo'] = $this->url->link('/');
        } else {
            $json['errors'] = $this->validator->flattenMessages();
        }

        return $this->json($json);
    }

     /**
     * Valider le formulaire
     *
     * @param int $id
     * @return bool
     */
    private function isValid()
    {
        $this->validator->required('first_name', 'First Name is Required');
        $this->validator->required('last_name', 'Last Name is Required');
        $this->validator->required('password')->minLen('password', 8)->match('password', 'confirm_password', 'Confirm Password Should Match Password');
        $this->validator->required('email')->email('email');
        $this->validator->unique('email', ['users', 'email']);
        $this->validator->requiredFile('image')->image('image');

        return $this->validator->passes();
    }
}