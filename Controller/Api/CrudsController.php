<?php

use PHPMailer\PHPMailer\PHPMailer;
// use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require_once dirname(dirname(__DIR__)) . '/vendor/autoload.php';
App::uses('AppController', 'Controller');



class CrudsController extends AppController
{

    public $uses = ['Crud', 'CrudBeneficiary', 'CrudStatus', 'CrudUpload'];

    public function beforeFilter()
    {

        parent::beforeFilter();

        $this->RequestHandler->ext = 'json';
    }

    public function index()
    {

        // Get the current page number from the query, default to 1 if not set
        $page = isset($this->request->query['page']) ? $this->request->query['page'] : 1;
        $conditions = array();

        // check if there is a search term in the query
        if (isset($this->request->query['search'])) {
            // Convert the search term to lowercase
            $search = strtolower($this->request->query['search']);
            // Add OR condition to search in name and email fields
            $conditions['OR'] = array(
                'LOWER(Crud.name) LIKE' => '%' . $search . '%',
                'LOWER(Crud.email) LIKE' => '%' . $search . '%',
            );
        }
        // filter by tab 
        if (isset($this->request->query['tab']) && is_numeric($this->request->query['tab'])) {
            $conditions['Crud.crudConditionId'] = (int)$this->request->query['tab'];
        }
        // only include records that are marked as visible
        $conditions['Crud.visible'] = true;

        // Set the pagination settingsd
        $paginatorSettings = array(
            'conditions' => $conditions, // Filetering conditions
            'limit' => 25, // Number of records per page
            'page' => $page, // Current page number
            "order" => array( 
                'Crud.name' => 'ASC'
            ),
            'contain' => 'CrudStatus' // load associated CrudStatus
        );

        $modelName = "Crud";
        // Apply paginator settings
        $this->Paginator->settings = $paginatorSettings;
        // Fetch paginated data
        $tmpData = $this->Paginator->paginate($modelName);
        // Get the pagination metadata
        $paginator = $this->request->params['paging'][$modelName];



        $cruds_ = array();
        // Format each record for the frontend response
        foreach ($tmpData as $crud) {
            $cruds_[] = array(
                'id'            => $crud['Crud']['id'],
                'name'          => properCase($crud['Crud']['name']),
                'age'           => $crud['Crud']['age'],
                'email'         => $crud['Crud']['email'],
                'character'     => $crud['Crud']['charac'],
                'visible'       => $crud['Crud']['visible'],
                'date-created'  => date('m/d/y', strtotime($crud['Crud']['created'])),
                'condition'    => $crud['Crud']['crudConditionId'],
                'status'        => isset($crud['CrudStatus']['name']) ? $crud['CrudStatus']['name'] : 'Unknown',
            );
        }
        // JSON response
        $response = array(
            'ok' => true,
            'msg' => 'index',
            'data' => $cruds_,
            'paginator' => $paginator,
        );
        // set the respnse to be serialized as JSON
        $this->set(array(
            'response' => $response,
            '_serialize' => 'response'
        ));
    }

    public function add($id = null)
    {
        App::uses('View', 'View'); // Load CakePHP's View class for rendering email template

        $data = $this->request->data;

        $crudInformation = new View(null, false);


        $mail = new PHPMailer(true);
        // Save the main data and associated records
        if ($this->Crud->saveAssociated($data)) {
            $crudId = $this->Crud->id;
        // handle multiple file uploads
            if (!empty($_FILES['file']['name'][0])) {
                $files = $_FILES['file'];
                $uploadPath = WWW_ROOT . 'uploads' . DS;
                for ($i = 0; $i < count($files['name']); $i++) {
                    if (!empty($files['name'][$i])) {
                        $fileName = time() . '' . basename($files['name'][$i]);
                        $fullPath = $uploadPath . $fileName;
                        // Move the uploaded file to the specified directory
                        if (move_uploaded_file($files['tmp_name'][$i], $fullPath)) {
                            // Save each file to the crud_files table
                            $this->CrudUpload->create();
                            $this->CrudUpload->save([
                                'crud_id'   => $crudId,
                                'filepath' => 'uploads/' . $fileName,
                                'filename' => $files['name'][$i]
                            ]);
                        }
                    }
                }
            }
            // Fetch the newly created user with associated data
            $newUser = $this->Crud->find('first', [
                'contain' => ['CrudStatus', 'CrudBeneficiary'],
                'conditions' => ['Crud.id' => $this->Crud->id],
            ]);

            $user = $newUser['Crud'];
            $beneficiaryNames = [];
            // compile a list  of beneficiary names
            if (!empty($newUser['CrudBeneficiary'])) {
                foreach ($newUser['CrudBeneficiary'] as $beneficiary) {
                    $beneficiaryNames[] = isset($beneficiary['name']) ? $beneficiary['name'] : '';
                }
            }
            $beneficiaryList = implode(', ', $beneficiaryNames);
            // set data for the email template
            $crudInformation->set([
                'recipientName'      => properCase($user['name']),
                'recipientAge'       => $user['age'],
                'recipientBirthday'  => $user['birthDate'],
                'recipientRole'      => isset($user['crudStatusId']) ? ($user['crudStatusId'] == 1 ? 'Regular' : ($user['crudStatusId'] == 2 ? 'Project-based' : 'Unknown')) : '',
                'recipientCharacter' => $user['charac'],
                'recipientEmail'     => $user['email'],
                'Beneficiaries'      => $beneficiaryList,
            ]);
            // Render the email body using the element
            $emailBody = $crudInformation->element('emailBodyAdd');
            $userEmail = $newUser['Crud']['email'];
            try {
                // Setup and send email
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'mercadobenedick024@gmail.com';
                $mail->Password = 'bjta euxv wmwt sjby';
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                // Email
                $mail->setFrom('mercadobenedick024@gmail.com', 'CRUD EMAIL');
                $mail->addAddress($userEmail);

                $mail->isHTML(true);
                $mail->Subject = 'New User Added';
                $mail->msgHTML($emailBody);

                $mail->send();
                // Debug('debug', 'Email sent');
            } catch (Exception $e) {
                // Debug('error', 'Mailer Error: ' . $mail->ErrorInfo);
            }
            $response = array(
                'ok' => true,
                'msg' => 'Saved!',
                'data' => $this->request->data,
            );

            $this->set(array(
                'response' => $response,
                '_serialize' => 'response'
            ));
        } else {
            $response = array(
                'ok' => false,
                'msg' => 'Not Saved!',
                'data' => $this->request->data,
            );
            $this->set(array(
                'response' => $response,
                '_serialize' => 'response'
            ));
        }
    }

    public function view($id = null)
    {
        // fetch a single CRUD record with its associated status, beneficiaries, and uploads
        $data = $this->Crud->find('first', array(
            'contain' => array(
                'CrudStatus',
                'CrudBeneficiary' => array(
                    // only include visible beneficiaries
                    'conditions' => array(
                        'CrudBeneficiary.visible' => true,
                    )
                ),
                'CrudUpload' => array(
                    // only include visible uploaded files
                    'conditions' => array(
                        'CrudUpload.visible' => true,
                    )
                ),
            ),
            'conditions' => array(
                'Crud.id' => $id, // match by id
                'Crud.visible' => true, // ensure the main record is visible

            )

        ));

        $response = array(
            'ok' => true,
            'msg' => 'Viewed!',
            'data' => $data,
        );

        $this->set(array(
            'response' => $response,
            '_serialize' => 'response'
        ));
    }

    public function edit($id = null)
    {
        App::uses('View', 'View');

        $data = $this->request->data;

        $crudInformation = new View(null, false);
        //fetch original data to compare with old condition
        $original = $this->Crud->find('first', array(
            'contain' => array(
                'CrudStatus',
                'CrudBeneficiary',
            ),
            'conditions' => array(
                'Crud.id' => $id,
            )
        ));
        // extract original values for email and status
        if (isset($original['Crud'])) {
            $oldConditionId = isset($original['Crud']['crudConditionId']) ? $original['Crud']['crudConditionId'] : null;
            $recipientEmail = isset($original['Crud']['email']) ? $original['Crud']['email'] : null;
            $recipientName = isset($original['Crud']['name']) ? properCase($original['Crud']['name']) : '';
            $recipientAge = isset($original['Crud']['age']) ? properCase($original['Crud']['age']) : '';
            $recipientBirthday = isset($original['Crud']['birthDate']) ? properCase($original['Crud']['birthDate']) : '';
            $recipientRole = isset($original['Crud']['crudStatusId'])
                ? properCase($original['Crud']['crudStatusId'] == 1 ? 'Regular' : ($original['Crud']['crudStatusId'] == 2 ? 'Project-based' : 'Unknown'))
                : '';
            $recipientCharacter = isset($original['Crud']['charac']) ? $original['Crud']['charac'] : '';
        } else {
            $oldConditionId = null;
            $recipientEmail = null;
            $recipientName = '';
            $recipientAge = '';
            $recipientBirthday = '';
            $recipientRole = '';
            $recipientCharacter = '';
        }
        // determine if email notification is needed
        $newConditionId = isset($data['Crud']['crudConditionId']) ? $data['Crud']['crudConditionId'] : null;
        $emailNeeded = $oldConditionId != $newConditionId;
        // collect names of all associated beneficiaries
        $beneficiaryNames = array();
        if (!empty($original['CrudBeneficiary'])) {
            foreach ($original['CrudBeneficiary'] as $beneficiary) {
                $beneficiaryNames[] = isset($beneficiary['name']) ? $beneficiary['name'] : '';
            }
        }


        $beneficiaryList = implode(', ', $beneficiaryNames);
        // prepare dynamic values for the email template
        $crudInformation->set(array(
            'oldStatus'             => ($oldConditionId == 1 ? 'Approved' : ($newConditionId == 2 ? 'Disapproved' : 'Pending')),
            'newStatus'             => ($newConditionId == 1 ? 'Approved' : ($newConditionId == 2 ? 'Disapproved' : 'Pending')),
            'recipientName'         => $recipientName,
            'recipientAge'          => $recipientAge,
            'recipientBirthday'     => $recipientBirthday,
            'recipientRole'         => $recipientRole,
            'recipientCharacter'    => $recipientCharacter,
            'recipientEmail'        => $recipientEmail,
            'Beneficiaries'         => $beneficiaryList
        ));
        // render the email body using the view element
        $emailBody = $crudInformation->element('emailBody');

        $mail = new PHPMailer(true);
        // save updated record with associations
        if ($this->Crud->saveAssociated($data)) {

            $crudId = $this->Crud->id;
            if (!empty($_FILES['file']['name'][0])) {
                $files = $_FILES['file'];
                $uploadPath = WWW_ROOT . 'uploads' . DS;
                for ($i = 0; $i < count($files['name']); $i++) {
                    if (!empty($files['name'][$i])) {
                        $fileName = time() . '' . basename($files['name'][$i]);
                        $fullPath = $uploadPath . $fileName;
                        if (move_uploaded_file($files['tmp_name'][$i], $fullPath)) {
                            $this->CrudUpload->create();
                            $this->CrudUpload->save([
                                'crud_id'   => $crudId,
                                'filepath' => 'uploads/' . $fileName,
                                'filename' => $files['name'][$i]
                            ]);
                        }
                    }
                }
            }


            if ($emailNeeded) {
                try {
                    // Server settings
                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com';
                    $mail->SMTPAuth = true;
                    $mail->Username = 'mercadobenedick024@gmail.com';
                    $mail->Password = 'bjta euxv wmwt sjby';
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                    $mail->Port = 587;

                    // Email
                    $mail->setFrom('mercadobenedick024@gmail.com', 'CRUD EMAIL');
                    $mail->addAddress($recipientEmail);

                    $mail->isHTML(true);
                    $mail->Subject = 'Status Updated';
                    $mail->msgHTML($emailBody);

                    $mail->send();
                    // Debug('debug', 'Email sent');
                } catch (Exception $e) {
                    // Debug('error', 'Mailer Error: ' . $mail->ErrorInfo);
                }
            }

            $response = array(
                'ok' => true,
                'msg' => 'Updated!',
                'data' => $this->request->data,
            );
        } else {
            $response = array(
                'ok' => false,
                'msg' => 'Not Updated!',
                'data' => $this->request->data,
            );
        }

        $this->set(array(
            'response' => $response,
            '_serialize' => 'response'
        ));
    }
    // hide for possible retrieve
    public function delete($id = null)
    {
        // Attempt to "soft delete" the record (e.g., set visible = false)
        if ($this->Crud->hide($id)) {
            $response = array(
                'ok' => true,
                'msg' => 'Deleted!',
                'data' => $id,
            );
        } else {
            $response = array(
                'ok' => false,
                'msg' => 'Not Deleted!',
                'data' => $id,
            );
        }

        $this->set(array(
            'response' => $response,
            '_serialize' => 'response'
        ));
    }
}
