<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bookings</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .font{
            font-weight: 700;  
        }

        .h1{
            color: white;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
        }

        .container-fluid{
            display: flex;
            justify-content: center;
            height: 80px;
            background-color: rgba(62, 105, 145, 0.95);
            align-items: center;
            margin-bottom: 20px;
        } 
    
        .card-custom {
            background-color:azure !important;
            border: 2px solid black !important;
            border-radius: 10px;
            transition: all 0.3s ease;
            height: 450px;
        }

        .card-custom:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
            border-color: #333 !important;
        }

/* Ensure proper spacing and alignment */
        .booking-card .row {
            margin: 0;
        }

        .booking-card .card-body {
            padding: 1.5rem;
        }
        .p-3{
            padding: 0 !important;
        }

        
    </style>
</head>
<body>
    <div class="container-fluid" >
          <p class="h1 font">Sports Facility Booking</p>
    </div>

<Section id= "Cards">
    <div class="container h-100">
        <div class="row h-100 justify-content-center align-items-center" >

    <!-- Ground Booking Card -->
            <div class="col-lg-4 col-md-6 col-sm-12 mb-3">
                <button type="button" class="btn text-decoration-none p-0 w-100" data-bs-toggle="modal" data-bs-target="#groundBookingModal">
                    <div class="card text-center card-custom">
                        <div class="card-body d-flex flex-column justify-content-center">
                            <img src="Sports/images/Football.webp" alt="Football" class="img-fluid mb-2">
                            <i class="fas fa-futbol fa-3x mb-3 text-dark"></i>
                            <h4 class="card-title text-dark"><b>Ground Booking</b></h4>
                            <p class="card-text text-muted">
                                Book outdoor sports grounds for football, cricket, and athletics
                            </p>
                        </div>
                    </div>
                </button>
            </div>

    <!-- Gym Booking Card -->
            <div class="col-lg-4 col-md-6 col-sm-12 mb-3">
                <button type="button" class="btn text-decoration-none p-0 w-100" data-bs-toggle="modal" data-bs-target="#gymBookingModal">
                    <div class="card text-center card-custom">
                        <div class="card-body d-flex flex-column justify-content-center">
                            <img src="Sports/images/weightlifting.jpeg" alt="Weightlifting" class="img-fluid mb-2">
                            <i class="fas fa-dumbbell fa-3x mb-3 text-dark"></i>
                            <h4 class="card-title text-dark"><b>Gym Booking</b></h4>
                            <p class="card-text text-muted">
                                Access our fully-equipped gym with modern equipment
                            </p>
                        </div>
                    </div>
                </button>
            </div>

    <!-- Indoor Booking Card -->
            <div class="col-lg-4 col-md-6 col-sm-12 mb-3">
                <button type="button" class="btn text-decoration-none p-0 w-100" data-bs-toggle="modal" data-bs-target="#indoorBookingModal">
                    <div class="card text-center card-custom">
                        <div class="card-body d-flex flex-column justify-content-center">
                            <img src="Sports/images/badminton.webp" alt="Badminton" class="img-fluid mb-2">
                            <i class="fas fa-table-tennis fa-3x mb-3 text-dark"></i>
                            <h4 class="card-title text-dark"><b>Indoor Booking</b></h4>
                            <p class="card-text text-muted">
                                Book indoor courts for badminton, basketball, and squash
                            </p>
                        </div>
                    </div>
                </button>
            </div>
        </div>
    </div>
</section>

<!-- Booked Details Section -->
<section id="booked-details" class="py-5" style="background-color: #e0e2e4ff;">
    <div class="container">
        <h1 class="text-center mb-4 font" style="color: rgba(62, 105, 145, 0.95); font-size: 3rem;">Booked Details</h1>
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead style="background-color:red; color: white;">
                    <tr>
                        <th>Facility</th>
                        <th>Date</th>
                        <th>Time Slot</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Ground Booking Samples -->
                    <tr>
                        <td>Ground Booking</td>
                        <td>2023-11-10</td>
                        <td>2:00 PM - 5:00 PM</td>
                        <td>Booked</td>
                    </tr>
                    <tr>
                        <td>Ground Booking</td>
                        <td>2023-11-15</td>
                        <td>10:00 AM - 12:00 PM</td>
                        <td>Booked</td>
                    </tr>
                    <!-- Gym Booking Samples -->
                    <tr>
                        <td>Gym Booking</td>
                        <td>2023-11-12</td>
                        <td>6:00 PM - 8:00 PM</td>
                        <td>Booked</td>
                    </tr>
                    <tr>
                        <td>Gym Booking</td>
                        <td>2023-11-18</td>
                        <td>9:00 AM - 11:00 AM</td>
                        <td>Booked</td>
                    </tr>
                    <!-- Indoor Booking Samples -->
                    <tr>
                        <td>Indoor Booking</td>
                        <td>2023-11-14</td>
                        <td>4:00 PM - 6:00 PM</td>
                        <td>Booked</td>
                    </tr>
                    <tr>
                        <td>Indoor Booking</td>
                        <td>2023-11-20</td>
                        <td>1:00 PM - 3:00 PM</td>
                        <td>Booked</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</section>

    <!-- Ground Booking Modal -->
<div class="modal fade" id="groundBookingModal" tabindex="-1" aria-labelledby="groundBookingModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="groundBookingModalLabel">Ground Booking Form</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="groundBookingForm">
                    <div class="mb-3">
                        <label for="groundName" class="form-label">Name</label>
                        <input type="text" class="form-control" id="groundName" required>
                    </div>
                    <div class="mb-3">
                        <label for="groundEmail" class="form-label">Email</label>
                        <input type="email" class="form-control" id="groundEmail" required>
                    </div>
                    <div class="mb-3">
                        <label for="groundPhone" class="form-label">Phone</label>
                        <input type="tel" class="form-control" id="groundPhone" required>
                    </div>
                    <div class="mb-3">
                        <label for="groundSportsType" class="form-label">Sports Type</label>
                        <select class="form-control" id="groundSportsType" required>
                            <option value="">Select Sport</option>
                            <option value="Cricket">Cricket</option>
                            <option value="Football">Football</option>
                            <option value="Athletics">Athletics</option>
                            <option value="Basketball">Basketball</option>
                            <option value="Volleyball">Volleyball</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="groundPlayers" class="form-label">Number of Players/Participants</label>
                        <input type="number" class="form-control" id="groundPlayers" min="1" required>
                    </div>
                    <div class="mb-3">
                        <label for="groundDate" class="form-label">Date</label>
                        <input type="date" class="form-control" id="groundDate" required>
                    </div>
                    <div class="mb-3">
                        <label for="groundTime" class="form-label">Time</label>
                        <input type="time" class="form-control" id="groundTime" required>
                    </div>
                    <div class="mb-3">
                        <label for="groundDuration" class="form-label">Duration (hours)</label>
                        <input type="number" class="form-control" id="groundDuration" min="1" required>
                    </div>
                    <div class="mb-3">
                        <label for="groundRequirements" class="form-label">Special Requirements</label>
                        <textarea class="form-control" id="groundRequirements" rows="3" placeholder="e.g., Tournament, Coaching Session, etc."></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit Booking</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Gym Booking Modal -->
<div class="modal fade" id="gymBookingModal" tabindex="-1" aria-labelledby="gymBookingModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="gymBookingModalLabel">Gym Booking Form</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="gymBookingForm">
                    <div class="mb-3">
                        <label for="gymName" class="form-label">Name</label>
                        <input type="text" class="form-control" id="gymName" required>
                    </div>
                    <div class="mb-3">
                        <label for="gymEmail" class="form-label">Email</label>
                        <input type="email" class="form-control" id="gymEmail" required>
                    </div>
                    <div class="mb-3">
                        <label for="gymPhone" class="form-label">Phone</label>
                        <input type="tel" class="form-control" id="gymPhone" required>
                    </div>
                    <div class="mb-3">
                        <label for="gymSessionType" class="form-label">Session Type</label>
                        <select class="form-control" id="gymSessionType" required>
                            <option value="">Select Session Type</option>
                            <option value="General Workout">General Workout</option>
                            <option value="Personal Training">Personal Training</option>
                            <option value="Group Class">Group Class</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="gymPeople" class="form-label">Number of People (for group bookings)</label>
                        <input type="number" class="form-control" id="gymPeople" min="1">
                    </div>
                    <div class="mb-3">
                        <label for="gymPreferredArea" class="form-label">Preferred Area</label>
                        <select class="form-control" id="gymPreferredArea" required>
                            <option value="">Select Area</option>
                            <option value="Cardio Zone">Cardio Zone</option>
                            <option value="Weight Training">Weight Training</option>
                            <option value="Functional Training">Functional Training</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="gymEquipment" class="form-label">Equipment Preference</label>
                        <textarea class="form-control" id="gymEquipment" rows="3" placeholder="Specific machines or areas"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="gymDate" class="form-label">Date</label>
                        <input type="date" class="form-control" id="gymDate" required>
                    </div>
                    <div class="mb-3">
                        <label for="gymTime" class="form-label">Time</label>
                        <input type="time" class="form-control" id="gymTime" required>
                    </div>
                    <div class="mb-3">
                        <label for="gymDuration" class="form-label">Duration (hours)</label>
                        <input type="number" class="form-control" id="gymDuration" min="1" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit Booking</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Indoor Booking Modal -->
<div class="modal fade" id="indoorBookingModal" tabindex="-1" aria-labelledby="indoorBookingModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="indoorBookingModalLabel">Indoor Booking Form</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="indoorBookingForm">
                    <div class="mb-3">
                        <label for="indoorName" class="form-label">Name</label>
                        <input type="text" class="form-control" id="indoorName" required>
                    </div>
                    <div class="mb-3">
                        <label for="indoorEmail" class="form-label">Email</label>
                        <input type="email" class="form-control" id="indoorEmail" required>
                    </div>
                    <div class="mb-3">
                        <label for="indoorPhone" class="form-label">Phone</label>
                        <input type="tel" class="form-control" id="indoorPhone" required>
                    </div>
                    <div class="mb-3">
                        <label for="indoorSportType" class="form-label">Sport Type</label>
                        <select class="form-control" id="indoorSportType" required>
                            <option value="">Select Sport</option>
                            <option value="Badminton">Badminton</option>
                            <option value="Basketball">Basketball</option>
                            <option value="Table Tennis">Table Tennis</option>
                            <option value="Squash">Squash</option>
                            <option value="Volleyball">Volleyball</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="indoorCourtSelection" class="form-label">Court Selection</label>
                        <select class="form-control" id="indoorCourtSelection" required>
                            <option value="">Select Court</option>
                            <option value="Court 1">Court 1</option>
                            <option value="Court 2">Court 2</option>
                            <option value="Court 3">Court 3</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="indoorNumPlayers" class="form-label">Number of Players</label>
                        <input type="number" class="form-control" id="indoorNumPlayers" min="1" required>
                    </div>
                    <div class="mb-3">
                        <label for="indoorCourtType" class="form-label">Court Type</label>
                        <select class="form-control" id="indoorCourtType" required>
                            <option value="">Select Court Type</option>
                            <option value="Singles">Singles</option>
                            <option value="Doubles">Doubles</option>
                            <option value="Full Court">Full Court</option>
                            <option value="Half Court">Half Court</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="indoorEquipmentRental" class="form-label">Equipment Rental</label>
                        <textarea class="form-control" id="indoorEquipmentRental" rows="3" placeholder="Rackets, balls, nets, etc."></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="indoorBookingPurpose" class="form-label">Booking Purpose</label>
                        <select class="form-control" id="indoorBookingPurpose" required>
                            <option value="">Select Purpose</option>
                            <option value="Casual play">Casual play</option>
                            <option value="Training">Training</option>
                            <option value="Tournament">Tournament</option>
                            <option value="Match">Match</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="indoorDate" class="form-label">Date</label>
                        <input type="date" class="form-control" id="indoorDate" required>
                    </div>
                    <div class="mb-3">
                        <label for="indoorTime" class="form-label">Time</label>
                        <input type="time" class="form-control" id="indoorTime" required>
                    </div>
                    <div class="mb-3">
                        <label for="indoorDuration" class="form-label">Duration (hours)</label>
                        <input type="number" class="form-control" id="indoorDuration" min="1" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit Booking</button>
                    </form>
                </div>
            </div>
        </div>
    </div> 
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    // Handle form submissions
    document.getElementById('groundBookingForm').addEventListener('submit', function(e) {
        e.preventDefault();
        alert('Ground booking submitted successfully!');
        // Close modal
        var modal = bootstrap.Modal.getInstance(document.getElementById('groundBookingModal'));
        modal.hide();
    });

    document.getElementById('gymBookingForm').addEventListener('submit', function(e) {
        e.preventDefault();
        alert('Gym booking submitted successfully!');
        // Close modal
        var modal = bootstrap.Modal.getInstance(document.getElementById('gymBookingModal'));
        modal.hide();
    });

    document.getElementById('indoorBookingForm').addEventListener('submit', function(e) {
        e.preventDefault();
        alert('Indoor booking submitted successfully!');
        // Close modal
        var modal = bootstrap.Modal.getInstance(document.getElementById('indoorBookingModal'));
        modal.hide();
    });
</script>

  <!-- Footer -->
  <footer class="bg-gray-800 text-gray-300 py-4 text-center">
    <p>&copy; 2025 Sabaragamuwa University Of Sri Lanka. All rights reserved.</p>
  </footer>

</body>
</html>
