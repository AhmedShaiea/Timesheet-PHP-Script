<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Session;
use Log;

class TimesheetEdited extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
		$userid = Session::get('UID');
		$user = DB::table('users')->where('id', '=', $userid)->get()->first();
		$username = ucfirst($user->first_name) . ' ' . ucfirst($user->last_name);
        return $this->subject('Timesheet Edited!')->view('emails.timesheet.edit')
                    ->with([
                        'username' => $username
                    ]);
    }
}
