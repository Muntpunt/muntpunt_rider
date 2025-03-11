<?php
use CRM_MuntpuntRider_ExtensionUtil as E;

class CRM_MuntpuntRider_Page_View extends CRM_Core_Page {
  private string $riderEventDate = '';
  private int $riderEventId = 0;

  public function run() {
    try {
      $this->processQueryParams();
      $this->setPageTitle();

      $eventFetcher = new CRM_MuntpuntRider_EventFetcher();

      if ($this->riderEventId) {
        $events = $eventFetcher->fetchEventsById($this->riderEventId);
      }
      else {
        $events = $eventFetcher->fetchEventsByDate($this->riderEventDate);
      }

      $this->assign('events', $events);
    }
    catch (Exception $e) {
      $this->assign('errorMessage', $e->getMessage());
    }

    parent::run();
  }

  private function processQueryParams(): void {
    $this->processQueryParamDate();
    $this->processQueryParamEvent();

    if (empty($this->riderEventDate) && empty($this->riderEventId)) {
      $this->riderEventDate = date('Y-m-d');
    }
  }

  private function processQueryParamDate(): void {
    $queryParamDate = CRM_Utils_Request::retrieve('datum', 'String');
    if (empty($queryParamDate)) {
      return;
    }

    if ($queryParamDate == 'vandaag') {
      $this->riderEventDate = date('Y-m-d');
      return;
    }

    if (mb_strlen($queryParamDate) < 10) {
      throw new Exception("De opgegeven datum ($queryParamDate) is niet in het formaat YYYY-MM-DD");
    }

    $y = substr($queryParamDate, 0, 4);
    $m = substr($queryParamDate, 5, 2);
    $d = substr($queryParamDate, 8, 2);

    if (checkdate($m, $d, $y) == FALSE) {
      throw new Exception("De opgegeven datum ($queryParamDate) is ongeldig. Formaat moet YYYY-MM-DD zijn.");
    }

    $this->riderEventDate = "$y-$m-$d";
  }

  private function processQueryParamEvent(): void {
    $id = CRM_Utils_Request::retrieve('event_id', 'Positive');
    if ($id) {
      $this->riderEventId = $id;
    }
  }

  private function setPageTitle(): void {
    $daysOfTheWeek = [
      1 => 'Maandag',
      2 => 'Dindag',
      3 => 'Woensdag',
      4 => 'Donderdag',
      5 => 'Vrijdag',
      6 => 'Zaterdag',
      7 => 'Zondag',
    ];
    $monthsOfTheYear = [
      1 => 'januari',
      2 => 'februari',
      3 => 'maart',
      4 => 'april',
      5 => 'Mei',
      6 => 'juni',
      7 => 'juli',
      8 => 'augustus',
      9 => 'september',
      10 => 'oktober',
      11 => 'november',
      12 => 'december',
    ];
    $numericDate = strtotime($this->riderEventDate . ' 12:00:00');
    $w = date('N', $numericDate);
    $d = date('j', $numericDate);
    $m = date('n', $numericDate);
    $y = date('Y', $numericDate);

    CRM_Utils_System::setTitle($daysOfTheWeek[$w] . " $d " . $monthsOfTheYear[$m] . " $y");
  }

}
