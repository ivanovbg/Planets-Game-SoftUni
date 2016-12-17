<?php

namespace AppBundle\Controller;


use AppBundle\Entity\GameResource;
use AppBundle\Entity\Messages;
use AppBundle\Entity\Planet;
use AppBundle\Entity\PlanetResource;
use AppBundle\Entity\User;
use AppBundle\Form\CreatePlanet;
use AppBundle\Form\EditUserProfile;
use AppBundle\Form\ForgotPassword;
use AppBundle\Form\SendMessage;
use AppBundle\Form\UserLogin;
use AppBundle\Form\UserRegistration;
use AppBundle\Services\GameService;
use AppBundle\Services\MailerService;
use Doctrine\ORM\EntityManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Constraints\DateTime;


class UserController extends Controller
{

    public function getUser()
    {
        return $this->get('session')->get('id');
    }
    public function getPlanet()
    {
        return $this->get('planet_service')->getPlanet();
    }

    /**
     * @Route("/user/register", name="user_register");
     * @Method("GET");
     */
    public function register()
    {
        $form = $this->createForm(UserRegistration::class);
        return $this->render("users/register.html.twig", [
            'form' => $form->createView()
        ]);

    }

    /**
     * @Route("user/register", name="user_register_post")
     * @Method("POST")
     * @param Request $request
     */
    public function registerPost(Request $request)
    {
        $user = new User();
        $form = $this->createForm(UserRegistration::class, $user);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $password = $this->get('security.password_encoder')->encodePassword($user, $user->getPassword());
            $user->setPassword($password);
            $user->setDateofregistration(new \DateTime("now"));
            $user_exit = $this->getDoctrine()->getRepository(User::class)->createQueryBuilder('cm');
            $user_exit
                ->select('cm')
                ->where($user_exit->expr()->orX(
                    $user_exit->expr()->eq('cm.username', ':username'),
                    $user_exit->expr()->eq('cm.email', ':email')
                ))
                ->setParameter('username', $user->getUsername())
                ->setParameter('email', $user->getEmail());
            $users = $user_exit->getQuery()->getScalarResult();
            if (!$users) {
                $this->get('user_service')->saveUser($user);
                $create_planet=$this->get('planet_service');
                $planet_name=$user->getUsername()."_planet";
                $create_planet->createnewPlanet($user, $planet_name);
                $send_message=$this->get('mail_service')->welcomeMessage("newuser", $user->getEmail(), $user->getUsername());
                return $this->redirectToRoute("user_login");
            } else {
                $this->addFlash(
                    'error', "Потребителското име или емейл адрес вече са използвани, моля променете ги!");
                return $this->redirectToRoute("user_register");
            }
        }
        return $this->redirectToRoute("user_register");
    }

    /**
     * @Route("/user/login", name="user_login");
     * @Method("GET");
     */
    public function login()
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('user_profile', [
                'id' => $this->getUser()]);
        }
        $form = $this->createForm(UserLogin::class);
        return $this->render("users/login.html.twig", [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/user/login", name="user_login_post")
     * @Method("POST")
     * @param Request $request
     */
    public function loginPost(Request $request)
    {
        $user = new User();
        $postData = $request->request->all();
        $form = $this->createForm(UserLogin::class);
        $username = $postData[$form->getName()]['username'];
        $password = $postData[$form->getName()]['password'];
        $user_exit_db = $this->getDoctrine()->getRepository(User::class);
        $user_exit = $user_exit_db->findOneBy(['username' => $username]);

        if ($user_exit && password_verify($password, $user_exit->getPassword())) {
            $this->get('session')->start();
            $this->get('session')->set('id', $user_exit->getId());
            return $this->redirectToRoute('index_page');
        } else {
            $this->addFlash(
                'error', "Грешно потребителско име и парола!"
            );
            return $this->redirectToRoute("user_login");
        }
    }
    /**
     * @Route("user/logout", name="user_logout")
     * @Method("GET")
     */

    public function userLogout()
    {
        $this->get('user_service')->logOut();
        return $this->redirectToRoute('user_login');
    }
    /**
     * @Route("user/profile/{id}", name="user_profile")
     * @Method("GET")
     */
    public function userProfile($id)
    {
        $user_info = $this->getDoctrine()->getRepository(User::class);
        $user_info = $user_info->findOneBy(['id' => $id]);
        if (!$user_info) {
            return $this->redirectToRoute('user_login');
        }
        return $this->render('users/profile.html.twig', [
            'user' => $user_info
        ]);

    }
    /**
     * @Route("user/forgotpassword", name="forgot_password")
     * @Method("GET")
     */

    public function forgotPassword()
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('user_profile', [
                'id' => $this->getUser()
            ]);
        }
        $form = $this->createForm(ForgotPassword::class);
        return $this->render('users/forgotpassword.html.twig',
            [
                'form' => $form->createView()
            ]);
    }
    /**
     * @Route("user/forgotpassword", name="forgot_password_post")
     * @Method("POST")
     * @param Request $request
     */
    public function forgotPasswordPost(Request $request)
    {
        $user = new User;
        $postData = $request->request->all();
        $form = $this->createForm(ForgotPassword::class);
        $username = $postData[$form->getName()]['username'];
        $email = $postData[$form->getName()]['email'];
        $user_exit = $this->getDoctrine()->getRepository(User::class);
        $user_exit = $user_exit->findOneBy(['username' => $username, 'email' => $email]);
        if ($user_exit) {
            $password_random = $this->get('help_service')->randomPassword();
            $password = $this->get('security.password_encoder')->encodePassword($user, $password_random);
            $user_exit->setPassword($password);
            $this->get('user_service')->saveUser($user_exit);
            $send_message=$this->get('mail_service')->forgotPasswordSend($password_random, "newpassword", $user_exit->getEmail());
            $this->addFlash(
                'error', "Изпратехме Ви нова парола за достъп на вашият емейл адрес. Може да се логнете с новата си парола "
            );
            return $this->redirectToRoute("user_login");
        } else {
            return $this->redirectToRoute('forgot_password');
        }


    }

    /**
     * @Route("user/edit", name="user_edit")
     * @Method("GET")
     */
    public function editUser()
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('user_login');
        }
        $userEntity = $this->getDoctrine()->getRepository(User::class)->findOneBy(['id' => $this->getUser()]);
        $form = $this->createForm(EditUserProfile::class);
        return $this->render('users/editprofile.html.twig', [
            'form' => $form->createView(),
            'user' => $userEntity

        ]);
    }

    /**
     * @Route("user/edit", name="user_edit_post")
     * @Method("POST")
     * @param Request $request
     */
    public function editUserPost(Request $request)
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('user_login');
        }
        $user = new User();
        $postData = $request->request->all();
        $form = $this->createForm(EditUserProfile::class);
        $email = $postData[$form->getName()]['email'];
        $new_password = $postData[$form->getName()]['new_password'];
        $password = $postData[$form->getName()]['password'];
        $full_name = $postData[$form->getName()]['full_name'];
        $db_check = $this->getDoctrine()->getRepository(User::class);
        $userEntity = $db_check->findOneBy(['id' => $this->getUser()]);
        $email_verify = $db_check->findOneBy(['email' => $email]);
        if (password_verify($password, $userEntity->getPassword())) {
            if ($email_verify && $email_verify->getId() != $this->getUser()) {
                $this->addFlash(
                    'error', "Въведеният емейл адрес, вече се използва!"
                );
                return $this->redirectToRoute("user_edit");
            }
            $userEntity->setEmail($email);
            $userEntity->setFullname($full_name);
            if ($new_password == null) {
                $this->get('user_service')->saveUser($userEntity);
                return $this->redirectToRoute('user_edit');
            } else {
                $password = $this->get('security.password_encoder')->encodePassword($userEntity, $new_password);
                $userEntity->setPassword($password);
                $this->get('user_service')->saveUser($userEntity);
                return $this->redirectToRoute('user_edit');
            }
        }
        $this->addFlash(
            'error', "Грешна текуща парола. Моля, опитайте отново!"
        );
        return $this->redirectToRoute("user_edit");
    }


    /**
     * @Route("/user/message", name="user_all_message")
     * @Method("GET")
     */
    public function showAllMessage()
    {
        if($this->getUser())
        {
            $user = $this->get('user_service')->getUserRepository();
            $messages = $this->getDoctrine()->getRepository(Messages::class)->findBy(['receiver'=>$user]);
            return $this->render("users/message/message.html.twig", [
                'messages'=>$messages
            ]);
        }
        return $this->redirectToRoute('user_login');
    }
    /**
     * @Route("/user/showsendmessage", name="user_all_sendmessage")
     * @Method("GET")
     */
    public function showSendMessage()
    {
        if($this->getUser())
        {
            $user = $this->get('user_service')->getUserRepository();
            $messages = $this->getDoctrine()->getRepository(Messages::class)->findBy(['sender'=>$user]);

            return $this->render("users/message/sendmessageall.html.twig", [
                'messages'=>$messages
            ]);
        }
        return $this->redirectToRoute('user_login');
    }

    /**
     * @Route("user/sendmessage", name="user_message_send")
     * @Method("GET")
     */
    public function sendMessage()
    {
        if($this->getUser())
        {
            $form = $this->createForm(SendMessage::class);
            return $this->render("users/message/sendmessage.html.twig", [
                'form' => $form->createView()
            ]);

        }
        return $this->redirectToRoute('user_login');
    }

    /**
     * @Route("user/sendmessage", name="user_message_send_post")
     * @Method("POST")
     * @param Request $request
     */
    public function sendMessagePost(Request $request)
    {
        if($this->getUser())
        {
            $message =  new Messages();
            $postData = $request->request->all();
            $form = $this->createForm(SendMessage::class);
            $username = $postData[$form->getName()]['receiver'];
            $text = $postData[$form->getName()]['text'];
            $user_exit = $this->getDoctrine()->getRepository(User::class)->findOneBy(['username'=>$username]);
            if(!$user_exit){
                $this->addFlash(
                    'error', "Опитвате се да изпратите съобщение до потребител, който не съществува!"
                );
                return $this->redirectToRoute('user_message_send');

            }
            $this->get('user_service')->sendPrivateMessage($user_exit, $this->get('user_service')->getUserRepository(), $text);
            $this->addFlash(
                'notice', "Вашето съобщение е изпратено успешно!"
            );
            return $this->redirectToRoute('user_all_message');
        }
        return $this->redirectToRoute('user_login');
    }

    /**
     *@Route("user/message/{id}", name="user_read_message")
     *@Method("GET")
     * */
    public function readMessage($id)
    {
        if($this->getUser())
        {
            $receiver= $this->getDoctrine()->getRepository(User::class)->findOneBy(['id'=>$this->getUser()]);
            $messageRepository=$this->getDoctrine()->getRepository(Messages::class)->findOneBy(['id'=>$id, 'receiver'=>$receiver]);
            $entityMenager = $this->getDoctrine()->getManager();
            if($messageRepository)
            {
                $messageRepository->setIsRead(1);
                $entityMenager->persist($messageRepository);
                $entityMenager->flush();
                return $this->render('/users/message/view.html.twig', [
                    'message'=>$messageRepository
                ]);
            }
            return $this->redirectToRoute('user_all_message');
        }
        return $this->redirectToRoute('user_login');
    }

    /**
     *@Route("user/sendmessage/{id}", name="user_read_sendmessage")
     *@Method("GET")
     * */
    public function readSendMessage($id)
    {
        if($this->getUser())
        {
            $sender= $this->getDoctrine()->getRepository(User::class)->findOneBy(['id'=>$this->getUser()]);
            $messageRepository=$this->getDoctrine()->getRepository(Messages::class)->findOneBy(['id'=>$id, 'sender'=>$sender]);

            if($messageRepository)
            {
                return $this->render('/users/message/view.html.twig', [
                    'message'=>$messageRepository
                ]);
            }
            return $this->redirectToRoute('user_all_message');
        }
        return $this->redirectToRoute('user_login');
    }

    /**

     *@Route("user/message/delete/{messageId}",  name="delete_message")
     *@Method("GET")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */

    public function deleteMessage($messageId)
    {
        if($this->get('user_service')->getUser()) {
            $user = $this->get('user_service')->getUserRepository();
            $messageRepository = $this->getDoctrine()->getRepository(Messages::class)->findOneBy(['id' => $messageId]);
            if ($messageRepository && ($messageRepository->getSender() == $user || $messageRepository->getReceiver() == $user)) {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->remove($messageRepository);
                $entityManager->flush();
                return $this->redirectToRoute("user_all_message");
            }
            $this->redirectToRoute("user_all_message");
        }
        $this->redirectToRoute("user_login");

    }



}

