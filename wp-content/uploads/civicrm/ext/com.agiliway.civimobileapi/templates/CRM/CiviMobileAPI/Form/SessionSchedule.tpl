{if $session->get('userID') != 0}
  {include file="CRM/common/notifications.tpl"}
{/if}
<div class="crm-block crm-form-block schedule" id="agenda-schedule">
  <div class="event-session-tooltip" data-hover="0">
    <h4 class="event-session-tooltip-title"></h4>
    <div class="event-session-tooltip-row">
      <div class="event-session-tooltip-speakers"></div>
      <div class="event-session-tooltip-time"></div>
    </div>
    <p class="event-session-tooltip-description"></p>
    <i></i>
  </div>
  <div class="schedule-buttons">
    <div class="schedule-header">
      <button class="crm-button crm-button-type-cancel  crm-i-button todayDayButton" id="todayDayButton">
        Today
      </button>
      <button class="crm-button crm-button-type-cancel  crm-i-button" id="previousDayButton">
        <i class="fa fa-arrow-left" aria-hidden="true"></i>
      </button>
      <div class="selectedDate"></div>
      <button class="crm-button crm-button-type-cancel  crm-i-button nextDayButton" id="nextDayButton">
        <i class="fa fa-arrow-right" aria-hidden="true"></i>
      </button>
    </div>
  </div>
  <div class="schedule-table"></div>
</div>

{literal}
<style>
  #agenda-schedule {
    position: relative;
  }
  .event-session-tooltip {
    font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
    min-width: 200px;
    max-width: 350px;
    transform: translate(-50%, -100%);
    padding: 10px 20px;
    color: #444444;
    background-color: white;
    font-weight: normal;
    font-size: 13px;
    border-radius: 8px;
    position: absolute;
    z-index: 100;
    box-sizing: border-box;
    box-shadow: 0 0 5px rgba(0,0,0,0.2);
    display: none;
  }

  .event-session-tooltip-row {
    display: flex;
    align-items: center;
  }

  .event-session-tooltip-title {
    font-size: 16px;
    font-weight: bold;
  }

  .event-session-tooltip-time {
    color: #727272;
    font-size: 12px;
  }

  .event-session-tooltip-speakers {
    display: flex;
    flex-direction: row;
  }

  .event-session-tooltip-speakers-img-wrapper {
    width: 30px;
    height: 30px;
    border-radius: 100%;
    border: 3px solid white;
    overflow: hidden;
  }

  .event-session-tooltip-speakers-img-wrapper img {
    width: 30px;
    height: 30px;
    background: white;
    object-fit: cover;
  }

  .event-session-tooltip-speakers-img-wrapper .rest-speakers-number {
    width: 30px;
    height: 30px;
    font-size: 16px;
    font-weight: bold;
    padding: 6px 0;
    text-align: center;
    display: inline-block;
  }

  .event-session-tooltip i {
    position: absolute;
    top :100%;
    left: 50%;
    margin-left: -12px;
    width: 24px;
    height: 12px;
    overflow: hidden;
  }

  .event-session-tooltip i::after {
    content: '';
    position: absolute;
    width: 8px;
    height: 8px;
    left: 50%;
    transform: translate(-50%,-50%) rotate(45deg);
    background-color: white;
    box-shadow: 0 0 5px rgba(0,0,0,0.5);
  }

  #nextDayButton {
    margin-left:6px;
  }
  .todayDayButton {
    position:absolute;
    right: 0;
  }
  .schedule-header {
    margin: 0 auto;
  }
  .schedule-buttons {
    position: relative;
    padding-bottom: 4px;
    display: flex;
    justify-content: space-between;
    margin: 10px 0;
  }
  .crm-block.schedule {
    background-color: white;
    border: 1px solid #d3d3d3;
    margin-top: 20px;
  }
  .schedule-table {
    overflow: scroll;
    position: relative;
    max-height: 500px;
  }
  .selectedDate {
    display: inline-block;
    font-size: 19px;
    font-weight: bolder;
  }

  .crm-container .event-session-item {
    overflow: hidden;
    white-space: nowrap;
    text-overflow: ellipsis;
    cursor: pointer;
    box-sizing: border-box;
    padding: 6px;
    position: absolute;
    left: 2px;
    width: calc(100% - 5px);
    background: white;
    border-width: 1px;
    border-style: solid;
    border-color: #ccc;
    text-align: left;
    z-index: 1;
    border-radius: 2px;
    display: block;
    color: #3E3E3E;
  }

  .crm-container .event-session-item:link, .crm-container .event-session-item:visited {
    color: #3E3E3E;
  }

  .session-data tr td {
    position: relative;
    text-align: center;
    border: 1px solid #d4d4d4;
    min-width: 100px;
  }
  .session-data tr td:first-child {
    min-width: 60px;
    overflow: hidden;
    white-space: nowrap;
  }

  .session-data table {
    background: #f3f8fd;
    position: relative;
    border-collapse: collapse;
  }

  .session-data thead th {
    position: -webkit-sticky;
    position: sticky;
    top: 0;
    z-index: 2;
    color: #354052;
    font-weight: normal;
    padding: 0;
  }
  .session-data thead {
    background: #f9f9f9;
  }
  .session-data thead th div {
    max-width: 620px;
    overflow: hidden;
    white-space: nowrap;
    text-overflow: ellipsis;
    padding: 4px;
    background: #f9f9f9;
    border-bottom: 2px solid #e1e1e3;
  }
  .session-data thead th:first-child {
    left: 0;
    z-index: 3;
  }
  .session-data tbody th div {
    position: absolute;
    text-align: center;
    top: calc(-50% + 12px);
    right: 2px;
  }
  .session-data tbody th {
    position: relative;
    padding: 8px;
    position: -webkit-sticky;
    position: sticky;
    left: 0;
    background: #FFF;
    font-size: 14px;
    font-weight: normal;
    z-index: 4;
    height: 30px;
    width: 50px;
  }
  .session-data tbody th div {
    width: 100%;
    text-align: right;
    font-size: 12px;
  }
  .session-data tbody th:first-child:before {
    top: -1px;
  }
  .session-data .event-sessions td {
    border: 0;
    height: 0;
    padding: 0;
    background: white;
  }
  .select2-container-multi .select2-choices .select2-search-field input {
    font-size: 11px;
  }
</style>

<script>
  if (CRM.$('.crm-actionlinks-bottom').length) {
    CRM.$('#agenda-schedule').detach().insertBefore('.crm-actionlinks-bottom');
  }
  var sessionScheduleData = ({/literal}{$session_schedule_data}{literal});

  const startDate = new Date(sessionScheduleData.start_and_end_time.start.replace(/\s+/g, 'T'));
  const endDate = new Date(sessionScheduleData.start_and_end_time.end.replace(/\s+/g, 'T'));
  const todayDate = new Date();

  var startDateTimeStamp = startDate.getTime() - startDate.getHours()*60*60*1000 - startDate.getMinutes()*60*1000;
  var endDateTimeStamp = endDate.getTime() - endDate.getHours()*60*60*1000 - endDate.getMinutes()*60*1000;
  var todayDateTimeStamp = todayDate.getTime() - todayDate.getHours()*60*60*1000 - todayDate.getMinutes()*60*1000;
  var selectedDate = 0;

  CRM.$(function ($) {

    $('.event-session-tooltip').hover(function(){
        $('.event-session-tooltip').data('hover', 1);
    },
    function(){
        $('.event-session-tooltip').data('hover', 0);
        hideTooltip();
    });

    if (todayDateTimeStamp < endDateTimeStamp && todayDateTimeStamp > startDateTimeStamp) {
      selectedDate = new Date(todayDate);
    } else if (todayDateTimeStamp >= endDateTimeStamp) {
      selectedDate = new Date(endDate);
    } else if (todayDateTimeStamp <= startDateTimeStamp) {
      selectedDate = new Date(startDate);
    }

    function generateVenueSchedule() {
      if ((selectedDate.getFullYear() == startDate.getFullYear())
      && (selectedDate.getMonth() == startDate.getMonth())
      && (selectedDate.getDate() == startDate.getDate())) {
        $("#previousDayButton").attr('disabled', 'disabled');
      }
      else {
        $("#previousDayButton").removeAttr('disabled');
      }
      if ((selectedDate.getFullYear() == endDate.getFullYear())
      && (selectedDate.getMonth() == endDate.getMonth())
      && (selectedDate.getDate() == endDate.getDate())) {
        $("#nextDayButton").attr('disabled', 'disabled');
      }
      else {
        $("#nextDayButton").removeAttr('disabled');
      }
      if (todayDateTimeStamp > endDateTimeStamp
      || todayDateTimeStamp < startDateTimeStamp) {
        $("#todayDayButton").attr('disabled', 'disabled');
      }
      else {
        $("#todayDayButton").removeAttr('disabled');
      }

      $('.selectedDate')
        .html(sessionScheduleData['monthNames'][selectedDate.getMonth() + 1] + '-' + selectedDate.getDate() + '-' + selectedDate.getFullYear());

      generateHTMLTable(sessionScheduleData);

      setSessionsMarksOnSchedule(sessionScheduleData, selectedDate)
    }

    generateVenueSchedule();

    $("#previousDayButton").click(function () {
      selectedDate.setDate(selectedDate.getDate() - 1);
      generateVenueSchedule();
    });
    $("#nextDayButton").click(function () {
      selectedDate.setDate(selectedDate.getDate() + 1);
      generateVenueSchedule();
    });
    $("#todayDayButton").click(function () {
      selectedDate = new Date(todayDate);
      generateVenueSchedule();
    });

    function generateHTMLTable(sessionScheduleData) {
      var html = '<div class="session-data">';
      html += '<table><thead><tr><th></th>';
      sessionScheduleData['venues'].forEach(function (entry) {
        html += ('<th><div>'+ entry.name + '</div></th>');
      });
      html += "</thead><tbody>";
      for (i = 0; i < 24; i++) {
        html += '<tr class="schedule-row"><th class="schedule-hours"><div>' + sessionScheduleData['timeTypeArray'][i] + '</div></th>';
        sessionScheduleData['venues'].forEach(function (entry) {
          html += '<td></td>';
        });
        html += '</tr>';
      }
      html += ('</tbody><tfoot class="event-sessions"><td></td>');
      sessionScheduleData['venues'].forEach(function (entry) {
        html += ('<td data-venue="'+ entry.id + '" data-background="'+ entry.background_color +'" data-border="'+ entry.border_color +'"></td>');
      });
      html += ('</tfoot></table></div>');
      $('.schedule-table').html(html);
    }

    function setSessionsMarksOnSchedule(sessionScheduleData, selectedDate) {
      var tableHeight = $(".session-data tbody").innerHeight();
      var tableFooterHeight = $(".session-data tfoot").innerHeight();
      sessionScheduleData['event_session_values'].forEach(function (session) {
        var sessionId = session['id'];
        var sessionTitle = session['title'];
        var sessionStart = new Date(session['start_time'].replace(/\s+/g, 'T'));
        var sessionEnd = new Date(session['end_time'].replace(/\s+/g, 'T'));
        var sessionStartTime = new Date(session['start_time'].replace(/\s+/g, 'T'));
        if ((sessionStartTime.getFullYear() == selectedDate.getFullYear()) &&
          (sessionStartTime.getMonth() == selectedDate.getMonth()) &&
          (sessionStartTime.getDate() == selectedDate.getDate())) {

          session['venue_id'].split(',').forEach(function (entry) {
            var start_time = sessionStart.getHours() * 60 + sessionStart.getMinutes();
            var end_time = sessionEnd.getHours() * 60 + sessionEnd.getMinutes();
            var height = (end_time - start_time) * tableHeight / (24 * 60);
            var bottom = tableHeight - (end_time * tableHeight / (24 * 60) - tableFooterHeight);
            var venueCol = $($("td[data-venue='" + entry + "']")[0]);
            var url = CRM.url('civicrm/civimobile/event/session', {
              action: 'view',
              reset: 1,
              id: sessionId
            });
            let sessionTime = generateTimeInterval(sessionStart, sessionEnd);
            venueCol.append(
              '<a href="' + url + '" onmouseout="CRM.$(this).removeClass(\'hover\');hideTooltip();" ' +
               'onmouseover="generateTooltipForSession(event,\'' + session['id'] + '\',\'' + sessionTime + '\')" class="event-session-item crm-popup" style="background:'+ venueCol.data('background') +';border-color:'+ venueCol.data('border') +';bottom:' + bottom + 'px; height:' + height + 'px;"' + ' >' + sessionTitle + '</br>' + sessionTime + '</a>'
            );
          });
        }
      });
      $("a.event-session-item").on('crmPopupFormSuccess', function () {
        updateCalendar();
      });
    }

    function updateCalendar() {
      CRM.api3('CiviMobileEventSession', 'get', {
        "sequential": 1,
        "event_id": sessionScheduleData.event_id
      }).then(function(result) {
        sessionScheduleData.event_session_values = result.values;
        $('.event-session-item').remove();
        setSessionsMarksOnSchedule(sessionScheduleData, selectedDate);
      });

      CRM.api3('CiviMobileSpeaker', 'get', {
        "sequential": 1,
        "event_id": sessionScheduleData.event_id
      }).then(function(result) {
        sessionScheduleData.speakers = result.values;
      });
    }
  });

  function generateTimeInterval(dateStartObject, dateEndObject) {
    if (sessionScheduleData['timeTypeArray'][0]  == '12:00 AM'){
      return (convert24To12(dateStartObject.getHours(), ((dateStartObject.getMinutes() < 10 ? '0' : '') + dateStartObject.getMinutes())) +
      ' ─ ' + convert24To12(dateEndObject.getHours(), ((dateEndObject.getMinutes() < 10 ? '0' : '') + dateEndObject.getMinutes())));
    } else {
      return ((dateStartObject.getHours() < 10 ? '0' : '') + dateStartObject.getHours()) +
      ':' + ((dateStartObject.getMinutes() < 10 ? '0' : '') + dateStartObject.getMinutes()) +
      ' ─ ' + ((dateEndObject.getHours() < 10 ? '0' : '') + dateEndObject.getHours()) + ':' +
      ((dateEndObject.getMinutes() < 10 ? '0' : '') + dateEndObject.getMinutes());
    }
  }

  function convert24To12(time24, minutes) {
    var hourIn24 = time24;
    var hourIn12 = (hourIn24 % 12) || 12;
    hourin12 = (hourIn12 < 10)?("0"+hourIn12):hourIn12;
    return (hourin12 + ':' + minutes + (hourIn24 < 12 ? " AM" : " PM"));
  }

  function generateTooltipForSession(e, id, formattedTime) {
    CRM.$(e.target).addClass('hover');

    const session = sessionScheduleData.event_session_values.find(value => value.id === id);
    const agendaOffset = CRM.$('#agenda-schedule').offset();

    var slicedDescription = session.description.slice(0,86);
    if (slicedDescription.length < session.description.length) {
      slicedDescription += '...';
    }
    let speakerImagesHtml = '';

    for(let index = 0; index < session.speakers_names.length; index++) {
      let speakerData = sessionScheduleData.speakers.find(value => value.contact_id === session.speakers_names[index].contact_id);
      if (index >= 5) {
        let venueBackground = CRM.$(e.target).css('background');
        speakerImagesHtml += "<div class='event-session-tooltip-speakers-img-wrapper' style='background:" + venueBackground + ";margin-left:-6px;z-index:" + (session.speakers_names.length - index) + "; margin-right: 12px'>" +
         "<span class='rest-speakers-number'>+" + (session.speakers_names.length - index) + "</span>" +
        "</div>";
        break;
      }
      speakerImagesHtml += "<div title='" + speakerData.display_name + "' class='event-session-tooltip-speakers-img-wrapper' style='margin-left:" + (index > 0 ? -12 : 0) + "px;z-index:" + (session.speakers_names.length - index) + "; margin-right:" + (index + 1 === session.speakers_names.length ? 12 : 0) + "px'>" +
       "<img src='" + (speakerData.image_URL.length ? speakerData.image_URL: sessionScheduleData.default_user_image) + "' alt='" + speakerData.display_name + "'/>" +
      "</div>";
    }

    if (!CRM.$('.event-session-tooltip').data('hover')) {
      CRM.$('.event-session-tooltip .event-session-tooltip-title').text(session.title);
      CRM.$('.event-session-tooltip .event-session-tooltip-time').text(formattedTime);
      CRM.$('.event-session-tooltip .event-session-tooltip-description').text(slicedDescription);
      CRM.$('.event-session-tooltip .event-session-tooltip-speakers').html(speakerImagesHtml);
      CRM.$('.event-session-tooltip').css({top: (e.pageY - agendaOffset.top - 15) + 'px ', left: (e.pageX - agendaOffset.left - 15) + 'px'});
      CRM.$('.event-session-tooltip').show();
    }
  }

  function hideTooltip() {
    setTimeout(function () {
      if (!CRM.$('.event-session-tooltip').data('hover') && CRM.$('.event-session-item.hover').length == 0) {
        CRM.$('.event-session-tooltip').hide();
      }
    }, 200);
  }


</script>
{/literal}
