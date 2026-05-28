<?php
session_start();
require_once __DIR__ . '/autoloader.php';

if (empty($_SESSION['is_logged'])) {
    header('Location: /projet-web-gl21-chabiba/pages/auth/login.php');
    exit();
}

$bookingRepo = new BookingRepository();
$userId = $_SESSION['email'];
$deletedBooking = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_booking_id'])) {
    $bookingId = intval($_POST['delete_booking_id']);
    $booking = $bookingRepo->findById($bookingId);
    if ($booking && $booking->user_id === $userId) {
        $bookingRepo->deleteById($bookingId);
        $deletedBooking = true;
    }
}

$bookings = $bookingRepo->getByUser($userId);
$pageTitle = 'HIKI - My bookings';
$pageActive = 'bookings';
$extraStyles = ['css/pages/bookings.css'];
include __DIR__ . '/includes/header.php';
?>
    <main class="container py-5">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-10">
                <h1 class="page-heading">My Bookings</h1>
                <?php if ($deletedBooking): ?>
                    <div class="alert alert-success" role="alert">
                        Booking removed successfully.
                    </div>
                <?php endif; ?>

                <?php if (empty($bookings)): ?>
                    <div class="bookings-empty">
                        <p>You have no bookings yet.</p>
                        <a class="btn btn-primary" href="/projet-web-gl21-chabiba/pages/catalogue/index.php">Browse camping sites</a>
                    </div>
                <?php else: ?>
                    <div class="bookings-list">
                        <?php foreach ($bookings as $booking): ?>
                            <article class="booking-card">
                                <div class="booking-card-header">
                                    <h2><?= htmlspecialchars($booking->site_name ?: 'Camping site') ?></h2>
                                    <form method="POST" action="/projet-web-gl21-chabiba/pages/bookings.php" onsubmit="return confirm('Delete this booking?');">
                                        <input type="hidden" name="delete_booking_id" value="<?= (int)$booking->id ?>">
                                        <button class="btn btn-sm btn-outline-danger" type="submit">Delete</button>
                                    </form>
                                </div>
                                <div class="booking-details">
                                    <div><strong>From:</strong> <?= htmlspecialchars($booking->start_date) ?></div>
                                    <div><strong>To:</strong> <?= htmlspecialchars($booking->end_date) ?></div>
                                    <div><strong>People:</strong> <?= (int)$booking->people ?></div>
                                    <div><strong>Booked on:</strong> <?= htmlspecialchars($booking->created_at) ?></div>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </main>
</body>
</html>
