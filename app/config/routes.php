<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');
/**
 * ------------------------------------------------------------------
 * LavaLust - an opensource lightweight PHP MVC Framework
 * ------------------------------------------------------------------
 *
 * MIT License
 *
 * Copyright (c) 2020 Ronald M. Marasigan
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @package LavaLust
 * @author Ronald M. Marasigan <ronald.marasigan@yahoo.com>
 * @since Version 1
 * @link https://github.com/ronmarasigan/LavaLust
 * @license https://opensource.org/licenses/MIT MIT License
 */

/*
| -------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------
| Here is where you can register web routes for your application.
|
|
*/

//Main Route
$router->get('/', 'Home::index');
$router->get('/rooms', 'Home::rooms');
$router->get('/tours', 'Home::tours');
$router->get('/contact', 'Home::contact');
$router->get('/book/room/{id}', 'Home::book');
$router->post('/book/process', 'Home::process_booking');
$router->get('/booking/success', 'Home::booking_success');
$router->get('/booking/confirm', 'Home::confirm');
$router->post('/booking/finalize', 'Home::finalize');
$router->get('/my-profile', 'Home::my_profile');
$router->get('/change-avatar', 'Home::change_avatar'); 
$router->post('/profile/upload-avatar', 'Home::process_avatar_upload');
$router->get('/edit-profile', 'Home::edit_profile'); 
$router->post('/profile/update', 'Home::update_profile');

// Admin Dashboard
$router->get('/admin', 'Admin::dashboard');
$router->get('/admin/dashboard', 'Admin::dashboard');
$router->get('/admin/bookings/delete/{id}', 'Admin::delete_booking');

// Payment Routes
$router->get('/checkout/room', 'Payment::create_room_source');
$router->post('/checkout/room', 'Payment::create_room_source');
$router->get('/checkout/tour', 'Payment::create_tour_source');
$router->post('/checkout/tour', 'Payment::create_tour_source');
$router->get('/payment/success/room', 'Payment::handle_room_payment_success');
$router->get('/payment/success/tour', 'Payment::handle_tour_payment_success');
$router->get('/payment/cancel/(:any)', 'Payment::cancel/$1');
$router->get('/payment/success/room', 'Payment::handle_room_payment_success');
$router->get('/payment/success/tour', 'Payment::handle_tour_payment_success');
$router->get('/payment/cancel/(:any)', 'Payment::cancel/$1');

// GCash Payment API Routes
$router->post('/api/payment/gcash/create', 'Payment::api_create_gcash_source');
$router->get('/api/payment/gcash/status/(:any)', 'Payment::api_check_gcash_status/$1');

// Admin Routes
// Route for the main admin page (list of tours)
$router->get('/admin/tours', 'Admin::index');
// Route to show the form for adding a new tour
$router->get('/admin/add', 'Admin::add_tour');
// Route to handle the form submission
$router->post('/admin/save_tour', 'Admin::save_tour');
// Route for deleting a tour (the {id} is a placeholder)
$router->get('/admin/delete/{id}', 'Admin::delete_tour');
// Route to show the edit form for a specific tour
$router->get('/admin/edit_tour/{id}', 'Admin::edit_tour');
// Route to handle the update form submission
$router->post('/admin/update_tour', 'Admin::update_tour');
$router->get('/admin/invoice/view/{id}', 'Admin::invoice');


// Authentication Routes
$router->get('/login', 'Auth::login');
$router->get('/signup', 'Auth::signup');
$router->post('/auth/register', 'Auth::register');       
$router->post('/auth/authenticate', 'Auth::authenticate'); 
$router->get('/logout', 'Auth::logout');
$router->get('/admin/logout', 'Auth::admin_logout');
$router->get('/verify-email', 'Auth::verify_email');
$router->post('/auth/process-verification', 'Auth::process_verification');

// Password Reset Routes
$router->get('/forgot-password', 'Auth::forgot_password');
$router->post('/auth/send-reset-code', 'Auth::send_reset_code');
$router->get('/reset-password', 'Auth::reset_password');
$router->post('/auth/process-reset-password', 'Auth::process_reset_password');

// Google OAuth Routes
$router->get('/google-login', 'Auth::google_login');
$router->get('/google-callback', 'Auth::google_callback');

// Room Type Routes
$router->get('/admin/room-types', 'Admin::room_types');
$router->get('/admin/room-types/add', 'Admin::add_room_type');
$router->post('/admin/room-types/save', 'Admin::save_room_type');
$router->get('/admin/room-types/edit/{id}', 'Admin::edit_room_type');
$router->post('/admin/room-types/update', 'Admin::update_room_type');
$router->get('/admin/room-types/delete/{id}', 'Admin::delete_room_type');


// Room Management Routes
$router->get('/admin/rooms', 'Admin::rooms');
$router->get('/admin/rooms/add', 'Admin::add_room');
$router->post('/admin/rooms/save', 'Admin::save_room');
$router->get('/admin/rooms/edit/{id}', 'Admin::edit_room');
$router->post('/admin/rooms/update', 'Admin::update_room');
$router->get('/admin/rooms/delete/{id}', 'Admin::delete_room');


// Booking Management Routes
$router->get('/admin/bookings', 'Admin::bookings');
$router->get('/admin/bookings/add', 'Admin::add_booking');
$router->post('/admin/bookings/save', 'Admin::save_booking');
$router->get('/admin/bookings/edit/{id}', 'Admin::edit_booking');
$router->post('/admin/bookings/update', 'Admin::update_booking');


// Resource Management Routes
$router->get('/admin/resources', 'Admin::resources');
$router->get('/admin/resources/add', 'Admin::add_resource');
$router->post('/admin/resources/save', 'Admin::save_resource');
$router->get('/admin/resources/edit/{id}', 'Admin::edit_resource');
$router->post('/admin/resources/update', 'Admin::update_resource');
$router->get('/admin/resources/delete/{id}', 'Admin::delete_resource');


// Tour Booking Routes
$router->get('/book/tour/{id}', 'Home::book_tour');
$router->post('/book/process-tour', 'Home::process_tour_booking');
$router->get('/booking/confirm-tour', 'Home::confirm_tour_booking');
$router->post('/booking/finalize-tour', 'Home::finalize_tour_booking');
$router->get('/admin/tour-bookings', 'Admin::tour_bookings');
$router->get('/admin/tour-booking/manage/{id}', 'Admin::manage_tour_booking');
$router->get('/tour/{id}', 'Home::tour_detail');
$router->post('/admin/tour-booking/assign-resource', 'Admin::assign_resource');
$router->get('/admin/tour-booking/unassign/{id}/{booking_id}', 'Admin::unassign_resource');

// Guest Management Routes
$router->get('/admin/guests', 'Admin::guests');
$router->get('/admin/guest/view/{id}', 'Admin::view_guest');
$router->get('/admin/guest/delete/{id}', 'Admin::delete_guest');

// Reports & Analytics Routes
$router->get('/admin/reports', 'Admin::reports');
$router->get('/admin/export-bookings', 'Admin::export_bookings');

// Quick Status Update Routes
$router->post('/admin/booking/update-status/{id}/{status}', 'Admin::update_booking_status');
$router->post('/admin/tour-booking/update-status/{id}/{status}', 'Admin::update_tour_booking_status');
