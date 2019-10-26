<?php

declare(strict_types=1);

namespace Talav\UserBundle\Event;

/**
 * Contains all events thrown in the TalavUserBundle.
 */
final class TalavUserEvents
{
    /**
     * The SECURITY_IMPLICIT_LOGIN event occurs when the user is logged in programmatically.
     *
     * This event allows you to access the response which will be sent.
     *
     * @Event("Talav\UserBundle\Event\UserEvent")
     */
    public const SECURITY_IMPLICIT_LOGIN = 'talav_user.security.implicit_login';

    /**
     * The CHANGE_PASSWORD_SUCCESS event occurs when the change password form is submitted successfully.
     *
     * This event allows you to set the response instead of using the default one.
     *
     * @Event("FOS\UserBundle\Event\FormEvent")
     */
    public const CHANGE_PASSWORD_SUCCESS = 'talav_user.change_password.edit.success';

//    /**
//     * The PROFILE_EDIT_SUCCESS event occurs when the profile edit form is submitted successfully.
//     *
//     * This event allows you to set the response instead of using the default one.
//     *
//     * @Event("FOS\UserBundle\Event\FormEvent")
//     */
//    const PROFILE_EDIT_SUCCESS = 'talav_user.profile.edit.success';
//    /**
//     * The PROFILE_EDIT_COMPLETED event occurs after saving the user in the profile edit process.
//     *
//     * This event allows you to access the response which will be sent.
//     *
//     * @Event("FOS\UserBundle\Event\FilterUserResponseEvent")
//     */
//    const PROFILE_EDIT_COMPLETED = 'talav_user.profile.edit.completed';

    /**
     * The REGISTRATION_COMPLETED event occurs after saving the user in the registration process.
     *
     * This event allows you to access the response which will be sent.
     *
     * @Event("Talav\UserBundle\Event\UserEvent")
     */
    public const REGISTRATION_COMPLETED = 'talav_user.registration.completed';

//    /**
//     * The REGISTRATION_CONFIRM event occurs just before confirming the account.
//     *
//     * This event allows you to access the user which will be confirmed.
//     *
//     * @Event("FOS\UserBundle\Event\GetResponseUserEvent")
//     */
//    const REGISTRATION_CONFIRM = 'talav_user.registration.confirm';

    /**
     * The REGISTRATION_CONFIRMED event occurs after confirming the account.
     *
     * This event allows you to access the response which will be sent.
     */
    public const REGISTRATION_CONFIRMED = 'talav_user.registration.confirmed';

    /**
     * The RESET_REQUEST_SUCCESS event occurs when the resetting form is submitted successfully.
     *
     * This events allows you to process communication around resetting password
     */
    public const RESET_REQUEST_SUCCESS = 'talav_user.reset.request';

    /**
     * The RESETTING_RESET_COMPLETED event occurs after saving the user in the resetting process.
     *
     * This event allows you to access the response which will be sent.
     *
     * @Event("Talav\UserBundle\Event\UserEvent")
     */
    public const RESETTING_RESET_COMPLETED = 'talav_user.resetting.reset.completed';

//    /**
//     * The RESETTING_SEND_EMAIL_INITIALIZE event occurs when the send email process is initialized.
//     *
//     * This event allows you to set the response to bypass the email confirmation processing.
//     * The event listener method receives a FOS\UserBundle\Event\GetResponseNullableUserEvent instance.
//     *
//     * @Event("FOS\UserBundle\Event\GetResponseNullableUserEvent")
//     */
//    const RESETTING_SEND_EMAIL_INITIALIZE = 'talav_user.resetting.send_email.initialize';
//    /**
//     * The RESETTING_SEND_EMAIL_CONFIRM event occurs when all prerequisites to send email are
//     * confirmed and before the mail is sent.
//     *
//     * This event allows you to set the response to bypass the email sending.
//     * The event listener method receives a FOS\UserBundle\Event\GetResponseUserEvent instance.
//     *
//     * @Event("FOS\UserBundle\Event\GetResponseUserEvent")
//     */
//    const RESETTING_SEND_EMAIL_CONFIRM = 'talav_user.resetting.send_email.confirm';
//    /**
//     * The RESETTING_SEND_EMAIL_COMPLETED event occurs after the email is sent.
//     *
//     * This event allows you to set the response to bypass the the redirection after the email is sent.
//     * The event listener method receives a FOS\UserBundle\Event\GetResponseUserEvent instance.
//     *
//     * @Event("FOS\UserBundle\Event\GetResponseUserEvent")
//     */
//    const RESETTING_SEND_EMAIL_COMPLETED = 'talav_user.resetting.send_email.completed';

    /**
     * The USER_CREATED event occurs when the user is created with UserManipulator.
     *
     * This event allows you to access the created user and to add some behaviour after the creation.
     *
     * @Event("FOS\UserBundle\Event\UserEvent")
     */
    public const USER_CREATED = 'talav_user.user.created';

//    /**
//     * The USER_PASSWORD_CHANGED event occurs when the user is created with UserManipulator.
//     *
//     * This event allows you to access the created user and to add some behaviour after the password change.
//     *
//     * @Event("FOS\UserBundle\Event\UserEvent")
//     */
//    const USER_PASSWORD_CHANGED = 'talav_user.user.password_changed';
//    /**
//     * The USER_ACTIVATED event occurs when the user is created with UserManipulator.
//     *
//     * This event allows you to access the activated user and to add some behaviour after the activation.
//     *
//     * @Event("FOS\UserBundle\Event\UserEvent")
//     */
//    const USER_ACTIVATED = 'talav_user.user.activated';

    /**
     * The USER_DEACTIVATED event occurs when the user is created with UserManipulator.
     *
     * This event allows you to access the deactivated user and to add some behaviour after the deactivation.
     *
     * @Event("FOS\UserBundle\Event\UserEvent")
     */
    public const USER_DEACTIVATED = 'talav_user.user.deactivated';

//    /**
//     * The USER_PROMOTED event occurs when the user is created with UserManipulator.
//     *
//     * This event allows you to access the promoted user and to add some behaviour after the promotion.
//     *
//     * @Event("FOS\UserBundle\Event\UserEvent")
//     */
//    const USER_PROMOTED = 'talav_user.user.promoted';
//    /**
//     * The USER_DEMOTED event occurs when the user is created with UserManipulator.
//     *
//     * This event allows you to access the demoted user and to add some behaviour after the demotion.
//     *
//     * @Event("FOS\UserBundle\Event\UserEvent")
//     */
//    const USER_DEMOTED = 'talav_user.user.demoted';
}
