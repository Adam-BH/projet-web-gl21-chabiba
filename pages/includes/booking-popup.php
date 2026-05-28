<?php
if (session_status() === PHP_SESSION_NONE) session_start();
$loggedIn = isset($_SESSION['user']);
?>
<!-- Booking popup modal -->
<div id="bookingModal" class="booking-modal" aria-hidden="true" style="display:none;">
    <div class="booking-modal-backdrop"></div>
    <div class="booking-modal-panel">
        <h3 id="bookingModalTitle">Book site</h3>
        <form id="bookingForm" method="POST" action="/projet-web-gl21-chabiba/pages/catalogue/book.php">
            <input type="hidden" name="site_id" id="bookingSiteId" value="">
            <div>
                <label>Start date</label>
                <input type="date" name="start_date" id="bookingStart" required>
            </div>
            <div>
                <label>End date</label>
                <input type="date" name="end_date" id="bookingEnd" required>
            </div>
            <div>
                <label>People</label>
                <input type="number" name="people" id="bookingPeople" min="1" value="1" required>
            </div>
            <div id="bookingAvailability" style="margin-top:8px;color:#ddd"></div>
            <div style="margin-top:12px;display:flex;gap:8px;justify-content:flex-end;">
                <button type="button" id="bookingCancel" class="btn">Cancel</button>
                <?php if ($loggedIn): ?>
                    <button type="submit" id="bookingConfirm" class="btn btn-primary">Confirm booking</button>
                <?php else: ?>
                    <a href="/projet-web-gl21-chabiba/pages/auth/login.php" id="bookingLogin" class="btn btn-primary">Log in to book</a>
                <?php endif; ?>
            </div>
        </form>
    </div>
</div>

<style>
.booking-modal{position:fixed;inset:0;display:flex;align-items:center;justify-content:center;z-index:1200}
.booking-modal-backdrop{position:absolute;inset:0;background:rgba(0,0,0,0.6)}
.booking-modal-panel{position:relative;background:#0b0b0b;padding:18px;border-radius:8px;min-width:300px;max-width:520px;border:1px solid rgba(255,255,255,0.04)}
.booking-modal-panel label{display:block;font-size:13px;margin-bottom:4px}
.booking-modal-panel input[type=date],.booking-modal-panel input[type=number]{width:100%;padding:8px;border-radius:6px;border:1px solid rgba(255,255,255,0.06);background:#0b0b0b;color:#fff}
.booking-modal-panel .btn{padding:8px 12px;border-radius:6px;border:1px solid rgba(255,255,255,0.16);background:rgba(255,255,255,0.05);color:#f9f9f9;text-decoration:none}
.btn-primary{background: linear-gradient(135deg, #8fd3ff 0%, #ffa86d 100%); color: #0d0f16;}
</style>

<script src="/projet-web-gl21-chabiba/js/booking.js"></script>
