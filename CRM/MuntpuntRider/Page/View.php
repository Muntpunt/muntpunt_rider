<?php
use CRM_MuntpuntRider_ExtensionUtil as E;

class CRM_MuntpuntRider_Page_View extends CRM_Core_Page {
  private string $riderEventDate = '';
  private int $riderEventId = 0;

  public function run() {
    CRM_Utils_System::setTitle('Muntpunt');

    try {
      $this->processQueryParams();

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

}
