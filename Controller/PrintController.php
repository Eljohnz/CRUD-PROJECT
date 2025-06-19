<?php
App::import('Vendor', 'fpdf', array('file' => 'fpdf/fpdf.php'));


class PrintController extends AppController
{
  public $uses = array('Crud');
  public $layout = null;

  public function generate_pdf()
  {
    $this->autoRender = false;
    $tab = $this->request->query('tab');
    $page = isset($this->request->query['page']) ? $this->request->query['page'] : 1;

    $conditions = [];

    if (in_array($tab, ['1', '2', '3'])) {
      $conditions['Crud.crudConditionId'] = (int)$tab;
    }

    if (isset($this->request->query['search'])) {
      $search = strtolower($this->request->query['search']);
      $conditions['Crud.name LIKE'] = '%' . $search .  '%';
    }

    $conditions['Crud.visible'] = true;

    $data = $this->Crud->find('all', [
      'conditions' => $conditions,
      'conditions' => $conditions,
      'limit' => 5,
      'page' => $page,
      "order" => array(
        'Crud.name' => 'ASC'
      )
    ]);

    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(0, 12, 'User List', 0, 1, 'C');
    $pdf->Ln(2);

    $colWidths = [10, 40, 20, 60, 40]; // total: 170
    $tableWidth = array_sum($colWidths);

    $pageWidth = $pdf->GetPageWidth();
    $xOffset = ($pageWidth - $tableWidth) / 2;

    $pdf->SetFillColor(230, 230, 230);
    $pdf->SetFont('Arial', 'B', 11);
    $pdf->SetX($xOffset);
    $pdf->Cell($colWidths[0], 9, '#', 1, 0, 'C', true);
    $pdf->Cell($colWidths[1], 9, 'Name', 1, 0, 'C', true);
    $pdf->Cell($colWidths[2], 9, 'Age', 1, 0, 'C', true);
    $pdf->Cell($colWidths[3], 9, 'Email', 1, 0, 'C', true);
    $pdf->Cell($colWidths[4], 9, 'Status', 1, 1, 'C', true);

    // Table Rows
    $pdf->SetFont('Arial', '', 10);
    $i = 1;
    foreach ($data as $item) {
      $user = $item['Crud'];
      $status = $user['crudConditionId'] == 1 ? 'Approved' : ($user['crudConditionId'] == 2 ? 'Disapproved' : 'Pending');

      $pdf->SetX($xOffset);
      $pdf->Cell($colWidths[0], 8, $i++, 1, 0, 'C');
      $pdf->Cell($colWidths[1], 8, $user['name'], 1);
      $pdf->Cell($colWidths[2], 8, $user['age'], 1, 0, 'C');
      $pdf->Cell($colWidths[3], 8, $user['email'], 1);
      $pdf->Cell($colWidths[4], 8, $status, 1, 1, 'C');
    }


    $pdf->Output('D', 'User_List.pdf');
  }

  public function generate_pdf_view($id = null)
  {
    $this->autoRender = false;

    $data = $this->Crud->find('first', array(
      'contain' => array(
        'CrudStatus',
        'CrudBeneficiary',
      ),
      'conditions' => array(
        'Crud.id' => $id,
        'Crud.visible' => true,
      )
    ));

    $pdf = new FPDF();
    $pdf->AddPage();

    // Title
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(0, 12, 'User Information', 0, 1, 'C');
    $pdf->Ln(2);

    // User Info Section
    $pdf->SetFont('Arial', 'B', 11);
    $pdf->Cell(40, 8, 'Name:', 0, 0);
    $pdf->SetFont('Arial', '', 11);
    $pdf->Cell(80, 8, $data['Crud']['name'], 0, 1);

    $pdf->SetFont('Arial', 'B', 11);
    $pdf->Cell(40, 8, 'Age:', 0, 0);
    $pdf->SetFont('Arial', '', 11);
    $pdf->Cell(80, 8, $data['Crud']['age'], 0, 1);

    $pdf->SetFont('Arial', 'B', 11);
    $pdf->Cell(40, 8, 'Status:', 0, 0);
    $pdf->SetFont('Arial', '', 11);
    $pdf->Cell(80, 8, ($data['Crud']['crudStatusId'] == 1 ? 'Regular' : ($data['Crud']['crudStatusId'] == 2 ? 'Project-based' : 'Unknown')), 0, 1);

    $pdf->SetFont('Arial', 'B', 11);
    $pdf->Cell(40, 8, 'Birthday:', 0, 0);
    $pdf->SetFont('Arial', '', 11);
    $pdf->Cell(80, 8, $data['Crud']['birthDate'], 0, 1);

    $pdf->SetFont('Arial', 'B', 11);
    $pdf->Cell(40, 8, 'Character:', 0, 0);
    $pdf->SetFont('Arial', '', 11);
    $pdf->Cell(80, 8, $data['Crud']['charac'], 0, 1);

    $pdf->SetFont('Arial', 'B', 11);
    $pdf->Cell(40, 8, 'Condition:', 0, 0);
    $pdf->SetFont('Arial', '', 11);
    $pdf->Cell(80, 8, ($data['Crud']['crudConditionId'] == 1 ? 'Approved' : ($data['Crud']['crudConditionId'] == 2 ? 'Disapproved' : 'Pending')), 0, 1);

    $pdf->Ln(6);

    // Beneficiary Section
    $pdf->SetFont('Arial', 'B', 14);
    $pdf->Cell(0, 10, 'Beneficiaries', 0, 1, 'C');
    $pdf->Ln(1);

    // Define column widths
    $colWidths = [10, 60, 30, 50]; // Total: 150
    $tableWidth = array_sum($colWidths);
    $pageWidth = $pdf->GetPageWidth();
    $xOffset = ($pageWidth - $tableWidth) / 2;

    // Beneficiary Table Header
    $pdf->SetFont('Arial', 'B', 11);
    $pdf->SetFillColor(230, 230, 230);
    $pdf->SetX($xOffset);
    $pdf->Cell($colWidths[0], 8, '#', 1, 0, 'C', true);
    $pdf->Cell($colWidths[1], 8, 'Name', 1, 0, 'C', true);
    $pdf->Cell($colWidths[2], 8, 'Age', 1, 0, 'C', true);
    $pdf->Cell($colWidths[3], 8, 'Birthdate', 1, 1, 'C', true);

    // Beneficiary Table Rows
    $pdf->SetFont('Arial', '', 10);
    $i = 1;
    foreach ($data['CrudBeneficiary'] as $beneficiary) {
      if (!$beneficiary['visible']) continue;

      $pdf->SetX($xOffset);
      $pdf->Cell($colWidths[0], 8, $i++, 1, 0, 'C');
      $pdf->Cell($colWidths[1], 8, $beneficiary['name'], 1);
      $pdf->Cell($colWidths[2], 8, $beneficiary['age'], 1, 0, 'C');
      $pdf->Cell($colWidths[3], 8, $beneficiary['birthDate'], 1, 1, 'C');
    }


    $pdf->Output('D', 'User_Info.pdf');
  }
}
