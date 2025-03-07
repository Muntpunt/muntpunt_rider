<?php

class CRM_MuntpuntRider_EventFetcher {
  public function fetchEventsById(int $eventId) {
    $event = new CRM_MuntpuntRider_Event($eventId);

    return [$event->fields];
  }

  public function fetchEventsByDate(string $eventDate) {
    $startOfTheDay = $eventDate . ' 00:00:00';
    $endOfTheDay = $eventDate . ' 23:59:59';

    $sql = "
      select
        e.id
      from
        civicrm_event e
      inner join
        civicrm_value_extra_evenement_info ei on e.id = ei.entity_id
      where
        e.is_active = 1
      and
        ei.muntpunt_zalen is not null
      and (
        (e.start_date >= '$startOfTheDay' and e.start_date <= '$endOfTheDay')
      or
        (e.start_date < '$startOfTheDay' and e.end_date >= '$startOfTheDay')
      )
    ";

    $dao = CRM_Core_DAO::executeQuery($sql);
    $events = [];
    while ($dao->fetch()) {
      $event = new CRM_MuntpuntRider_Event($dao->id);
      $events[] = $event->fields;
    }

    return $events;
  }

}
