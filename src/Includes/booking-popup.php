<?php
if (session_status() === PHP_SESSION_NONE) session_start();
$loggedIn = isset($_SESSION['user']);
?>
<!-- Booking popup modal -->
<div id="bookingModal" class="booking-modal" aria-hidden="true" style="display:none;">
    <div class="booking-modal-backdrop"></div>
    <div class="booking-modal-panel">
        <h3 id="bookingModalTitle">Book site</h3>
        <form id="bookingForm" method="POST" action="/projet-web-gl21-chabiba/public_html/catalogue/book.php">
            <input type="hidden" name="site_id" id="bookingSiteId" value="">
            <div class="form-group">
                <label for="bookingStart">Start date</label>
                <input type="date" name="start_date" id="bookingStart" required class="form-input">
            </div>
            <div class="form-group">
                <label for="bookingEnd">End date</label>
                <input type="date" name="end_date" id="bookingEnd" required class="form-input">
            </div>
            <div class="form-group">
                <label for="bookingPeople">People</label>
                <input type="number" name="people" id="bookingPeople" min="1" value="1" required class="form-input">
            </div>
            <div id="bookingAvailability" class="booking-availability"></div>
            <div class="booking-actions">
                <button type="button" id="bookingCancel" class="btn btn-secondary">Cancel</button>
                <?php if ($loggedIn): ?>
                    <button type="submit" id="bookingConfirm" class="btn btn-primary">Confirm booking</button>
                <?php else: ?>
                    <a href="/projet-web-gl21-chabiba/public_html/auth/login.php" id="bookingLogin" class="btn btn-primary">Log in to book</a>
                <?php endif; ?>
            </div>
        </form>
    </div>
</div>

<script src="/projet-web-gl21-chabiba/js/booking.js"></script>
