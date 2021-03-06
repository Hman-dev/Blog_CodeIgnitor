<?php
defined('BASEPATH') OR exit('No direct script access allowed');
// on va toujours étendre cette classe ci_controller.c'est une méthode codeignitor
class Site extends CI_Controller {

    public function index() {
        $data["title"] = "Page d'acceuil";
        
        $this->load->view('common/header',$data);
        $this ->load->view('site/index',$data);
        $this->load->view('common/footer',$data);
    }
  
    public function contact(){
        // un helper ne peut pas être une classe.
        $this->load->helper("form");
        $this->load->library('form_validation');

        $data["title"] = "Contact";

        $this->load->view('common/header', $data);

        if($this->form_validation->run()) {
            // TODO: envoyer le mail
            $this->load->library('email');
            $this->config->load('email', TRUE);
            $this->email->initialize($this->config->item('email'));

            $this->email->from($this->input->post('email'), $this->input->post('name'));
            $this->email->to('promoweb1@yopmail.com');
            $this->email->subject($this->input->post('title'));
            $this->email->message($this->input->post('message'));
           
            
            
            if($this->email->send()){
                $data['result_class'] = "alert-success";
                $data['result_message'] = "Merci de nous avoir envoyé ce mail. Nous y répondrons dans les meilleurs délais.";
            } else {
                $data['result_class'] = "alert-danger";
                $data['result_message'] = "Votre message n'a pas pu être envoyé. Nous mettons tout en oeuvre pour résoudre le problème.";
                // Ne faites jamais ceci dans le "vrai monde".Le code qui suit est pour nous mais pas
                //  pour l'utilisateur le mettre après en commentaire
                $data['result_message'] .= "<pre>\n";
                $data['result_message'] .= $this->email->print_debugger();
                $data['result_message'] .= "</pre>\n";
                $this->email->clear();
            }
        $this->load->view('site/contact_result', $data);
    }else {
        $this->load->view('site/contact', $data);
    }
    $this->load->view('common/footer', $data);
}

    public function apropos(){ 
        // traitement ou préparation de données.ici $data on définit quel sera le titre de la page.
        $data["title"] = "À propos de moi..."; /**/ 

        // création du rendu ou chargement des vues.
        $this->load->view('common/header',$data);
        $this ->load->view('site/apropos',$data);
        $this->load->view('common/footer',$data);
        
    }
    
    public function session_test() {
        $this->session->count ++;
        echo"Valeur :" . $this->session->count;
    }

    public function connexion() {
        $this->load->helper("form");
        $this->load->library('form_validation');

        $data["title"] = "Identification";

        if($this->form_validation->run()) {
            $username = $this->input->post('username');
            $password = $this->input->post('password');
            $this->auth_user->login( $username, $password);
            if($this->auth_user->is_connected) {
                redirect('index');
            } else {
                $data['login_error'] = "Échec de l'authentification";
            }
        }

        $this->load->view('common/header', $data);
        $this->load->view('site/connexion', $data);
        $this->load->view('common/footer', $data);
    }

        function deconnexion() {
            $this->auth_user->logout();
            redirect('index');
        }


}