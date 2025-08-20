@extends('backend.layouts.app')
@section('title', 'System Configuration | General')
@push('style')
    <link rel="stylesheet" href="{{ asset('backend/assets/css/dropify.min.css') }}">
@endpush
@section('content')
<div class="row mt-5">
    <div class="col-lg-12 mx-auto col-md-12">
        <div class="card mb-4">
            <div class="card-header">
                <h1 class="h5 mb-0">OTP SMS Templates</h1>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data" class="content_form">
                    @csrf
                    <div class="row">
                        <div class="col-md-12 form-group mb-3">
                            <div class="align-items-start">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="nav flex-column nav-pills me-3" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                                            <button class="nav-link active" id="v-pills-order-confirmation-tab" data-bs-toggle="pill" data-bs-target="#v-pills-order-confirmation" type="button" role="tab" aria-controls="v-pills-order-confirmation" aria-selected="true">
                                                Order Confirmation
                                            </button>
                                            <button class="nav-link" id="v-pills-payment-confirmation-tab" data-bs-toggle="pill" data-bs-target="#v-pills-payment-confirmation" type="button" role="tab" aria-controls="v-pills-payment-confirmation" aria-selected="false">
                                                Payment Confirmation
                                            </button>
                                            <button class="nav-link" id="v-pills-order-shipped-tab" data-bs-toggle="pill" data-bs-target="#v-pills-order-shipped" type="button" role="tab" aria-controls="v-pills-order-shipped" aria-selected="false">
                                                Order Shipped
                                            </button>
                                            <button class="nav-link" id="v-pills-delivery-status-change-tab" data-bs-toggle="pill" data-bs-target="#v-pills-delivery-status-change" type="button" role="tab" aria-controls="v-pills-delivery-status-change" aria-selected="false">
                                                Order Delivered
                                            </button>
                                            <button class="nav-link" id="v-pills-otp-for-login-signin-tab" data-bs-toggle="pill" data-bs-target="#v-pills-otp-for-login-signin" type="button" role="tab" aria-controls="v-pills-otp-for-login-signin" aria-selected="false">
                                                OTP for Login/Signup
                                            </button>
                                            <button class="nav-link" id="v-pills-password-reset-otp-tab" data-bs-toggle="pill" data-bs-target="#v-pills-password-reset-otp" type="button" role="tab" aria-controls="v-pills-password-reset-otp" aria-selected="false">
                                                Password Reset OTP
                                            </button>
                                            <button class="nav-link" id="v-pills-cart-abandonment-reminder-tab" data-bs-toggle="pill" data-bs-target="#v-pills-cart-abandonment-reminder" type="button" role="tab" aria-controls="v-pills-cart-abandonment-reminder" aria-selected="false">
                                                Cart Abandonment Reminder
                                            </button>
                                            <button class="nav-link" id="v-pills-order-processing-update-tab" data-bs-toggle="pill" data-bs-target="#v-pills-order-processing-update" type="button" role="tab" aria-controls="v-pills-order-processing-update" aria-selected="false">
                                                Order Processing Update
                                            </button>
                                            <button class="nav-link" id="v-pills-estimated-delivery-date-tab" data-bs-toggle="pill" data-bs-target="#v-pills-estimated-delivery-date" type="button" role="tab" aria-controls="v-pills-estimated-delivery-date" aria-selected="false">
                                                Estimated Delivery Date
                                            </button>
                                            <button class="nav-link" id="v-pills-out-for-delivery-tab" data-bs-toggle="pill" data-bs-target="#v-pills-out-for-delivery" type="button" role="tab" aria-controls="v-pills-out-for-delivery" aria-selected="false">
                                                Out for Delivery Notification
                                            </button>
                                            <button class="nav-link" id="v-pills-order-return-tab" data-bs-toggle="pill" data-bs-target="#v-pills-order-return" type="button" role="tab" aria-controls="v-pills-order-return" aria-selected="false">
                                                Return/Refund Status
                                            </button>
                                            <button class="nav-link" id="v-pills-special-offer-tab" data-bs-toggle="pill" data-bs-target="#v-pills-special-offer" type="button" role="tab" aria-controls="v-pills-special-offer" aria-selected="false">
                                                Special Offers & Discounts
                                            </button>
                                            <button class="nav-link" id="v-pills-product-back-in-stock-tab" data-bs-toggle="pill" data-bs-target="#v-pills-product-back-in-stock" type="button" role="tab" aria-controls="v-pills-product-back-in-stock" aria-selected="false">
                                                Product Back in Stock Alert
                                            </button>
                                            <button class="nav-link" id="v-pills-subscription-renewal-reminder-tab" data-bs-toggle="pill" data-bs-target="#v-pills-subscription-renewal-reminder" type="button" role="tab" aria-controls="v-pills-subscription-renewal-reminder" aria-selected="false">
                                                Subscription Renewal Reminder
                                            </button>
                                            <button class="nav-link" id="v-pills-loyalty-points-update-tab" data-bs-toggle="pill" data-bs-target="#v-pills-loyalty-points-update" type="button" role="tab" aria-controls="v-pills-loyalty-points-update" aria-selected="false">
                                                Loyalty Points Update
                                            </button>
                                            <button class="nav-link" id="v-pills-customer-feedback-request-tab" data-bs-toggle="pill" data-bs-target="#v-pills-customer-feedback-request" type="button" role="tab" aria-controls="v-pills-customer-feedback-request" aria-selected="false">
                                                Customer Feedback Request
                                            </button>
                                            <button class="nav-link" id="v-pills-account-activity-alert-tab" data-bs-toggle="pill" data-bs-target="#v-pills-account-activity-alert" type="button" role="tab" aria-controls="v-pills-account-activity-alert" aria-selected="false">
                                                Account Activity Alert
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="tab-content" id="v-pills-tabContent">
                                            <div class="tab-pane fade show active" id="v-pills-order-confirmation" role="tabpanel" aria-labelledby="v-pills-order-confirmation-tab">
                                                <div class="row">
                                                    <div class="col-md-12 form-group mb-3">
                                                        <label for="sms_phone_number_verification_template">SMS Body</label>
                                                        <textarea name="sms_phone_number_verification_template" id="sms_phone_number_verification_template" class="form-control" cols="30" rows="4">{{ get_settings('sms_phone_number_verification_template') }}</textarea>
                                                        <span class="text-danger">Do not change the variable [[ ____ ]]</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="v-pills-payment-confirmation" role="tabpanel" aria-labelledby="v-pills-payment-confirmation-tab">
                                                <div class="row">
                                                    <div class="col-md-12 form-group mb-3">
                                                        <label for="sms_password_reset_template">SMS Body</label>
                                                        <textarea name="sms_password_reset_template" id="sms_password_reset_template" class="form-control" cols="30" rows="4">{{ get_settings('sms_password_reset_template') }}</textarea>
                                                        <span class="text-danger">Do not change the variable [[ ____ ]]</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="v-pills-order-shipped" role="tabpanel" aria-labelledby="v-pills-order-shipped-tab">
                                                <div class="row">
                                                    <div class="col-md-12 form-group mb-3">
                                                        <label for="sms_order_placement_status">Activation</label>
                                                        <select name="sms_order_placement_status" id="sms_order_placement_status" class="form-control select">
                                                            <option {{ get_settings('sms_order_placement_status') == 1 ? 'selected' : '' }} value="1">Activate</option>
                                                            <option {{ get_settings('sms_order_placement_status') == 0 ? 'selected' : '' }} value="0">Deactivate</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-12 form-group mb-3">
                                                        <label for="sms_online_order_placement_template">SMS Body</label>
                                                        <textarea name="sms_online_order_placement_template" id="sms_online_order_placement_template" class="form-control" cols="30" rows="4">{{ get_settings('sms_online_order_placement_template') }}</textarea>
                                                        <span class="text-danger">Do not change the variable [[ ____ ]]</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="v-pills-delivery-status-change" role="tabpanel" aria-labelledby="v-pills-delivery-status-change-tab">
                                                <div class="row">
                                                    <div class="col-md-12 form-group mb-3">
                                                        <label for="sms_delivery_status_change">Activation</label>
                                                        <select name="sms_delivery_status_change" id="sms_delivery_status_change" class="form-control select">
                                                            <option {{ get_settings('sms_delivery_status_change') == 1 ? 'selected' : '' }} value="1">Activate</option>
                                                            <option {{ get_settings('sms_delivery_status_change') == 0 ? 'selected' : '' }} value="0">Deactivate</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-12 form-group mb-3">
                                                        <label for="sms_delivery_status_change_template">SMS Body</label>
                                                        <textarea name="sms_delivery_status_change_template" id="sms_delivery_status_change_template" class="form-control" cols="30" rows="4">{{ get_settings('sms_delivery_status_change_template') }}</textarea>
                                                        <span class="text-danger">Do not change the variable [[ ____ ]]</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="v-pills-otp-for-login-signin" role="tabpanel" aria-labelledby="v-pills-otp-for-login-signin-tab">
                                                <div class="row">
                                                    <div class="col-md-12 form-group mb-3">
                                                        <label for="sms_payment_status_change_template">SMS Body</label>
                                                        <textarea name="sms_payment_status_change_template" id="sms_payment_status_change_template" class="form-control" cols="30" rows="4">{{ get_settings('sms_payment_status_change_template') }}</textarea>
                                                        <span class="text-danger">Do not change the variable [[ ____ ]]</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="v-pills-password-reset-otp" role="tabpanel" aria-labelledby="v-pills-password-reset-otp-tab">
                                                <div class="row">
                                                    <div class="col-md-12 form-group mb-3">
                                                        <label for="sms_assign_delivery_boy_template">SMS Body</label>
                                                        <textarea name="sms_assign_delivery_boy_template" id="sms_assign_delivery_boy_template" class="form-control" cols="30" rows="4">{{ get_settings('sms_assign_delivery_boy_template') }}</textarea>
                                                        <span class="text-danger">Do not change the variable [[ ____ ]]</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="v-pills-cart-abandonment-reminder" role="tabpanel" aria-labelledby="v-pills-cart-abandonment-reminder-tab">
                                                <div class="row">
                                                    <div class="col-md-12 form-group mb-3">
                                                        <label for="sms_cart_abandonment_reminder_status">Activation</label>
                                                        <select name="sms_cart_abandonment_reminder_status" id="sms_cart_abandonment_reminder_status" class="form-control select">
                                                            <option {{ get_settings('sms_cart_abandonment_reminder_status') == 1 ? 'selected' : '' }} value="1">Activate</option>
                                                            <option {{ get_settings('sms_cart_abandonment_reminder_status') == 0 ? 'selected' : '' }} value="0">Deactivate</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-12 form-group mb-3">
                                                        <label for="sms_cart_abandonment_reminder_template">SMS Body</label>
                                                        <textarea name="sms_cart_abandonment_reminder_template" id="sms_cart_abandonment_reminder_template" class="form-control" cols="30" rows="4">{{ get_settings('sms_cart_abandonment_reminder_template') }}</textarea>
                                                        <span class="text-danger">Do not change the variable [[ ____ ]]</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="v-pills-order-processing-update" role="tabpanel" aria-labelledby="v-pills-order-processing-update-tab">
                                                <div class="row">
                                                    <div class="col-md-12 form-group mb-3">
                                                        <label for="sms_order_processing_update_status">Activation</label>
                                                        <select name="sms_order_processing_update_status" id="sms_order_processing_update_status" class="form-control select">
                                                            <option {{ get_settings('sms_cart_abandonment_reminder_status') == 1 ? 'selected' : '' }} value="1">Activate</option>
                                                            <option {{ get_settings('sms_cart_abandonment_reminder_status') == 0 ? 'selected' : '' }} value="0">Deactivate</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-12 form-group mb-3">
                                                        <label for="sms_order_processing_update_template">SMS Body</label>
                                                        <textarea name="sms_order_processing_update_template" id="sms_order_processing_update_template" class="form-control" cols="30" rows="4">{{ get_settings('sms_order_processing_update_template') }}</textarea>
                                                        <span class="text-danger">Do not change the variable [[ ____ ]]</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="v-pills-estimated-delivery-date" role="tabpanel" aria-labelledby="v-pills-estimated-delivery-date-tab">
                                                <div class="row">
                                                    <div class="col-md-12 form-group mb-3">
                                                        <label for="sms_estimated_delivery_date_status">Activation</label>
                                                        <select name="sms_estimated_delivery_date_status" id="sms_estimated_delivery_date_status" class="form-control select">
                                                            <option {{ get_settings('sms_estimated_delivery_date_status') == 1 ? 'selected' : '' }} value="1">Activate</option>
                                                            <option {{ get_settings('sms_estimated_delivery_date_status') == 0 ? 'selected' : '' }} value="0">Deactivate</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-12 form-group mb-3">
                                                        <label for="sms_estimated_delivery_date_template">SMS Body</label>
                                                        <textarea name="sms_estimated_delivery_date_template" id="sms_estimated_delivery_date_template" class="form-control" cols="30" rows="4">{{ get_settings('sms_estimated_delivery_date_template') }}</textarea>
                                                        <span class="text-danger">Do not change the variable [[ ____ ]]</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="v-pills-out-for-delivery" role="tabpanel" aria-labelledby="v-pills-out-for-delivery-tab">
                                                <div class="row">
                                                    <div class="col-md-12 form-group mb-3">
                                                        <label for="sms_out_for_delivery_status">Activation</label>
                                                        <select name="sms_out_for_delivery_status" id="sms_out_for_delivery_status" class="form-control select">
                                                            <option {{ get_settings('sms_out_for_delivery_status') == 1 ? 'selected' : '' }} value="1">Activate</option>
                                                            <option {{ get_settings('sms_out_for_delivery_status') == 0 ? 'selected' : '' }} value="0">Deactivate</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-12 form-group mb-3">
                                                        <label for="sms_out_for_delivery_template">SMS Body</label>
                                                        <textarea name="sms_out_for_delivery_template" id="sms_out_for_delivery_template" class="form-control" cols="30" rows="4">{{ get_settings('sms_out_for_delivery_template') }}</textarea>
                                                        <span class="text-danger">Do not change the variable [[ ____ ]]</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="v-pills-order-return" role="tabpanel" aria-labelledby="v-pills-order-return-tab">
                                                <div class="row">
                                                    <div class="col-md-12 form-group mb-3">
                                                        <label for="sms_order_return_status">Activation</label>
                                                        <select name="sms_order_return_status" id="sms_order_return_status" class="form-control select">
                                                            <option {{ get_settings('sms_order_return_status') == 1 ? 'selected' : '' }} value="1">Activate</option>
                                                            <option {{ get_settings('sms_order_return_status') == 0 ? 'selected' : '' }} value="0">Deactivate</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-12 form-group mb-3">
                                                        <label for="sms_order_return_template">SMS Body</label>
                                                        <textarea name="sms_order_return_template" id="sms_order_return_template" class="form-control" cols="30" rows="4">{{ get_settings('sms_order_return_template') }}</textarea>
                                                        <span class="text-danger">Do not change the variable [[ ____ ]]</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="v-pills-special-offer" role="tabpanel" aria-labelledby="v-pills-special-offer-tab">
                                                <div class="row">
                                                    <div class="col-md-12 form-group mb-3">
                                                        <label for="sms_special_offer_status">Activation</label>
                                                        <select name="sms_special_offer_status" id="sms_special_offer_status" class="form-control select">
                                                            <option {{ get_settings('sms_special_offer_status') == 1 ? 'selected' : '' }} value="1">Activate</option>
                                                            <option {{ get_settings('sms_special_offer_status') == 0 ? 'selected' : '' }} value="0">Deactivate</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-12 form-group mb-3">
                                                        <label for="sms_special_offer_template">SMS Body</label>
                                                        <textarea name="sms_special_offer_template" id="sms_special_offer_template" class="form-control" cols="30" rows="4">{{ get_settings('sms_special_offer_template') }}</textarea>
                                                        <span class="text-danger">Do not change the variable [[ ____ ]]</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="v-pills-product-back-in-stock" role="tabpanel" aria-labelledby="v-pills-product-back-in-stock-tab">
                                                <div class="row">
                                                    <div class="col-md-12 form-group mb-3">
                                                        <label for="sms_product_back_in_stock_status">Activation</label>
                                                        <select name="sms_product_back_in_stock_status" id="sms_product_back_in_stock_status" class="form-control select">
                                                            <option {{ get_settings('sms_product_back_in_stock_status') == 1 ? 'selected' : '' }} value="1">Activate</option>
                                                            <option {{ get_settings('sms_product_back_in_stock_status') == 0 ? 'selected' : '' }} value="0">Deactivate</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-12 form-group mb-3">
                                                        <label for="sms_product_back_in_stock_template">SMS Body</label>
                                                        <textarea name="sms_product_back_in_stock_template" id="sms_product_back_in_stock_template" class="form-control" cols="30" rows="4">{{ get_settings('sms_product_back_in_stock_template') }}</textarea>
                                                        <span class="text-danger">Do not change the variable [[ ____ ]]</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="v-pills-subscription-renewal-reminder" role="tabpanel" aria-labelledby="v-pills-subscription-renewal-reminder-tab">
                                                <div class="row">
                                                    <div class="col-md-12 form-group mb-3">
                                                        <label for="sms_subscription_renewal_reminder_status">Activation</label>
                                                        <select name="sms_subscription_renewal_reminder_status" id="sms_subscription_renewal_reminder_status" class="form-control select">
                                                            <option {{ get_settings('sms_subscription_renewal_reminder_status') == 1 ? 'selected' : '' }} value="1">Activate</option>
                                                            <option {{ get_settings('sms_subscription_renewal_reminder_status') == 0 ? 'selected' : '' }} value="0">Deactivate</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-12 form-group mb-3">
                                                        <label for="sms_subscription_renewal_reminder_template">SMS Body</label>
                                                        <textarea name="sms_subscription_renewal_reminder_template" id="sms_subscription_renewal_reminder_template" class="form-control" cols="30" rows="4">{{ get_settings('sms_subscription_renewal_reminder_template') }}</textarea>
                                                        <span class="text-danger">Do not change the variable [[ ____ ]]</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="v-pills-loyalty-points-update" role="tabpanel" aria-labelledby="v-pills-loyalty-points-update-tab">
                                                <div class="row">
                                                    <div class="col-md-12 form-group mb-3">
                                                        <label for="sms_loyalty_points_update_status">Activation</label>
                                                        <select name="sms_loyalty_points_update_status" id="sms_loyalty_points_update_status" class="form-control select">
                                                            <option {{ get_settings('sms_loyalty_points_update_status') == 1 ? 'selected' : '' }} value="1">Activate</option>
                                                            <option {{ get_settings('sms_loyalty_points_update_status') == 0 ? 'selected' : '' }} value="0">Deactivate</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-12 form-group mb-3">
                                                        <label for="sms_loyalty_points_update_template">SMS Body</label>
                                                        <textarea name="sms_loyalty_points_update_template" id="sms_loyalty_points_update_template" class="form-control" cols="30" rows="4">{{ get_settings('sms_loyalty_points_update_template') }}</textarea>
                                                        <span class="text-danger">Do not change the variable [[ ____ ]]</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="v-pills-customer-feedback-request" role="tabpanel" aria-labelledby="v-pills-customer-feedback-request-tab">
                                                <div class="row">
                                                    <div class="col-md-12 form-group mb-3">
                                                        <label for="sms_customer_feedback_request_status">Activation</label>
                                                        <select name="sms_customer_feedback_request_status" id="sms_customer_feedback_request_status" class="form-control select">
                                                            <option {{ get_settings('sms_customer_feedback_request_status') == 1 ? 'selected' : '' }} value="1">Activate</option>
                                                            <option {{ get_settings('sms_customer_feedback_request_status') == 0 ? 'selected' : '' }} value="0">Deactivate</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-12 form-group mb-3">
                                                        <label for="sms_customer_feedback_request_template">SMS Body</label>
                                                        <textarea name="sms_customer_feedback_request_template" id="sms_customer_feedback_request_template" class="form-control" cols="30" rows="4">{{ get_settings('sms_customer_feedback_request_template') }}</textarea>
                                                        <span class="text-danger">Do not change the variable [[ ____ ]]</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="v-pills-account-activity-alert" role="tabpanel" aria-labelledby="v-pills-account-activity-alert-tab">
                                                <div class="row">
                                                    <div class="col-md-12 form-group mb-3">
                                                        <label for="sms_account_activity_alert_status">Activation</label>
                                                        <select name="sms_account_activity_alert_status" id="sms_account_activity_alert_status" class="form-control select">
                                                            <option {{ get_settings('sms_account_activity_alert_status') == 1 ? 'selected' : '' }} value="1">Activate</option>
                                                            <option {{ get_settings('sms_account_activity_alert_status') == 0 ? 'selected' : '' }} value="0">Deactivate</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-12 form-group mb-3">
                                                        <label for="sms_account_activity_alert_template">SMS Body</label>
                                                        <textarea name="sms_account_activity_alert_template" id="sms_account_activity_alert_template" class="form-control" cols="30" rows="4">{{ get_settings('sms_account_activity_alert_template') }}</textarea>
                                                        <span class="text-danger">Do not change the variable [[ ____ ]]</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12 form-group text-end">
                            <button type="submit" id="submit" class="btn btn-soft-success">
                                <i class="bi bi-send"></i>
                                Update
                            </button>
                            <button type="button" style="display: none;" id="submitting" class="btn btn-soft-warning">
                                <div class="spinner-border" role="status">
                                    <span class="sr-only">Loading...</span>
                                </div>
                            </button>
                        </div>

                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@push('script')
    <script>
        _componentSelect();
        _formValidation();
    </script>
@endpush