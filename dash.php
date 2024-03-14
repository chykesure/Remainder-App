<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link
      href="https://cdn.jsdelivr.net/npm/remixicon@3.4.0/fonts/remixicon.css"
      rel="stylesheet"
    />
    <!-- Add Bootstrap CSS link -->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
    />
    <link rel="stylesheet" href="styles.css" />
    <title>Remainder App For Medication | Scheduler</title>
  </head>
  <body>
    <div class="container">
      <nav>
        <div class="nav__logo">Remainder App For Medication Scheduler</div>
        <!-- Add data-bs-toggle and data-bs-target attributes to trigger the modal -->
        <button class="btn" onclick="goHome()">Go Home</button>

        <button class="btn" onclick="openReminderModal()">Set Reminder</button>
      </nav>
      <header class="header">
        <div class="content">
          <h1><span>Get Quick</span><br />Reminder Services</h1>
          <p>
            In today's fast-paced world, access to prompt and efficient reminder services is of paramount importance. When managing your schedule or setting timely reminders, the ability to receive quick notification services can significantly impact the organization of your day. Whether it's remembering important meetings, deadlines, or personal events, our scheduler app ensures you stay on top of your commitments with swift and reliable reminder services.
          </p>
          <button class="btn" onclick="openReminderModal()">Medication Scheduler App</button>
        </div>
        <div class="image">
          <span class="image__bg"></span>
          <img src="assets/header-bg.png" alt="header image" />          
        </div>
      </header>

      <!-- Bootstrap-styled table -->
      <table class="table table-bordered table-striped mt-4">
        <thead>
          <tr>
            <th>Title</th>
            <th>Description</th>
            <th>Date & Time</th>
            <th>Action</th>
            <!-- Add more columns as needed -->
          </tr>
        </thead>
        <tbody id="remainderTableBody">
          
        </tbody>
      </table>
    </div>

    <!-- Bootstrap JS, Popper.js, and jQuery scripts (order is important) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>


    <!-- Modal HTML -->
    <div class="modal fade" id="reminderModal" tabindex="-1" role="dialog" aria-labelledby="reminderModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="reminderModalLabel">Set Reminder</h5>
            <button type="button" class="btn-close" onclick="closeReminderModal()" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <!-- Add your modal content here -->
            <!-- For example, you can add a form to set the reminder -->
            <form>
              <!-- Your form elements go here -->
              <div class="mb-3">
                <label for="title" class="form-label">Reminder Title</label>
                <input type="text" class="form-control" id="title">
              </div>
              <div class="mb-3">
                <label for="description" class="form-label">Reminder Message</label>
                <textarea class="form-control" id="description" rows="3"></textarea>
              </div>
              <div class="row">
                <!-- Reminder Date Column -->
                <div class="col-md-6 mb-3">
                  <label for="date" class="form-label">Reminder Date</label>
                  <input type="date" class="form-control" id="date">
                </div>
                <!-- Reminder Time Column -->
                <div class="col-md-6 mb-3">
                  <label for="time" class="form-label">Reminder Time</label>
                  <input type="time" class="form-control" id="time">
                </div>
              </div>
              <button type="button" class="btn btn-primary" onclick="scheduleRemainder();">Schedule Reminder</button>
            </form>
          </div>
        </div>
      </div>
    </div>
    <audio src="notify.mp3" id="notificationSound" loop></audio>


    <script>
      // Ask the user to allow notification access
      if ("Notification" in window) {
        Notification.requestPermission().then(function (permission) {
          if (Notification.permission !== "granted") {
            alert("Please allow notification access!");
            location.reload();
          }
        });
      }
    
      var timeoutIds = [];
      var isModalOpen = false;
      var notificationSound = document.getElementById("notificationSound");
    
      function openReminderModal() {
        isModalOpen = true;
        $('#reminderModal').modal('show');
      }
    
      function closeReminderModal() {
        isModalOpen = false;
        $('#reminderModal').modal('hide');
      }
    
      function startAudioLoop() {
        notificationSound.loop = true;
        notificationSound.play();
      }
    
      function stopAudioLoop() {
        notificationSound.loop = false;
        notificationSound.pause();
        notificationSound.currentTime = 0;
      }
    
      function scheduleRemainder() {
        var title = $("#title").val();
        var description = $("#description").val();
        var date = $("#date").val();
        var time = $("#time").val();
    
        var dateTimeString = date + " " + time;
        var scheduleTime = new Date(dateTimeString);
        var currentTime = new Date();
        var timeDifference = scheduleTime - currentTime;
    
        if (timeDifference > 0) {
          addReminder(title, description, dateTimeString);
    
          var timeoutID = setTimeout(function () {
            startAudioLoop();  // Start the audio loop
            var notification = new Notification(title, {
              body: description,
              requireInteraction: true,
            });
    
            // Add an onclose event handler
            notification.onclose = function () {
              stopAudioLoop();  // Stop the audio loop when the notification is closed
            };
          }, timeDifference);
    
          timeoutIds.push(timeoutID);
          closeReminderModal();
        } else {
          alert("The scheduled time is in the past");
        }
      }
    
      function addReminder(title, description, dateTimeString) {
        var tableBody = $("#remainderTableBody");
    
        var row = $("<tr></tr>");
    
        var titleCell = $("<td></td>").text(title);
        var descriptionCell = $("<td></td>").text(description);
        var dateTimeCell = $("<td></td>").text(dateTimeString);
        var actionCell = $("<td></td>").html('<button class="btn btn-warning" onclick="deleteReminder(this);">Delete</button>');
    
        row.append(titleCell, descriptionCell, dateTimeCell, actionCell);
        tableBody.append(row);
      }
    
      function deleteReminder(button) {
        var row = $(button).closest("tr");
        var index = row.index();
    
        clearTimeout(timeoutIds[index]);
        timeoutIds.splice(index, 1);
    
        stopAudioLoop();  // Stop the audio loop
        row.remove();
      }
    </script>

    
<script>
  function goHome() {
    window.location.href = "http://localhost/remainder";
  }
</script>
    
  </body>
</html>
