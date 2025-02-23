<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Controller;
use App\Http\Requests\SupportIndexRequest;
use App\Http\Requests\SupportStoreRequest;
use App\Mail\SupportMailManager;
use App\Models\Ticket;
use App\Models\User;
use Auth;
use Illuminate\Http\JsonResponse;
use Mail;

class MessagesController extends Controller
{

    /**
     * @param SupportIndexRequest $request
     * @return JsonResponse
     */
    public function index(SupportIndexRequest $request): JsonResponse
    {

        $order = $request->order;
        $sort = $request->sort;

        //Set default sorting
        if (!$order) $order = 'desc';
        if (!$sort) $sort = 'created_at';

        //Get user tickets
        $tickets = Ticket::where('user_id', Auth::user()->id)->orderBy($sort, $order);

        //Filter the tickets
        $tickets = $this->filterTickets($request, $tickets);

        //Paginate the tickets
        $tickets = $tickets->paginate(9);

        //Translate the tickets status
        $this->translateStatus($tickets);


        return response()->json(['data' => $tickets, 'success' => true, 'status' => 200], 200);


    }

    /**
     * @param SupportIndexRequest $request
     * @param $tickets
     * @return mixed
     */
    public function filterTickets(SupportIndexRequest $request, $tickets)
    {
        if ($request->date_range_from && $request->date_range_to) {
            $tickets = $tickets->whereBetween('created_at', [$request->date_range_from, $request->date_range_to]);
        }

        if ($request->status && $request->status != 'all') {
            $tickets = $tickets->where('status', '=', $request->status);
        }
        return $tickets;
    }

    /**
     * @param $tickets
     * @return void
     */
    public function translateStatus($tickets): void
    {
        foreach ($tickets as $key => $ticket) {
            $ticket->status = translate($ticket->status);
        }
    }


    /**
     * @param SupportStoreRequest $request
     * @return JsonResponse
     */
    public function store(SupportStoreRequest $request): JsonResponse
    {
        //Save ticket to database
        $ticket = $this->saveTicket($request);

        //Send notification to admin
        $this->send_support_mail_to_admin($ticket);

        return response()->json(['message' => translate('Ticket has been sent successfully'), 'success' => true, 'status' => 200], 200);

    }

    /**
     * @param SupportStoreRequest $request
     * @return Ticket
     */
    public function saveTicket(SupportStoreRequest $request): Ticket
    {
        $ticket = new Ticket;
        $ticket->code = max(100000, (Ticket::latest()->first() != null ? Ticket::latest()->first()->code + 1 : 0)) . date('s');
        $ticket->user_id = Auth::user()->id;
        $ticket->subject = $request->subject;
        $ticket->details = $request->details;
        $ticket->files = $request->attachments;
        $ticket->save();

        return $ticket;
    }

    /**
     * @param $ticket
     * @return void
     */
    public function send_support_mail_to_admin($ticket)
    {
        $array['view'] = 'emails.support';
        $array['subject'] = 'Support ticket Code is:- ' . $ticket->code;
        $array['from'] = env('MAIL_FROM_ADDRESS');
        $array['content'] = 'Hi. A ticket has been created. Please check the ticket.';
        $array['link'] = route('support_ticket.admin_show', encrypt($ticket->id));
        $array['sender'] = $ticket->user->name;
        $array['details'] = $ticket->details;

        try {
            Mail::to(User::where('user_type', 'admin')->first()->email)->queue(new SupportMailManager($array));
        } catch (\Exception $e) {
            // dd($e->getMessage());
        }
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function show($id): JsonResponse
    {
        //Get user ticket details
        $ticket = Ticket::where('id', $id)->where('user_id', Auth::user()->id)->first();

        //Check if the ticket exist
        if (!$ticket) {
            return $this->returnIfNotExist();
        }

        //Set client_viewed to true
        $this->setClientViewedToTrue($ticket);

        return response()->json(['data' => $ticket, 'success' => true, 'status' => 200], 200);
    }

    /**
     * @return JsonResponse
     */
    public function returnIfNotExist(): JsonResponse
    {
        return response()->json(['message' => translate('Message not found'), 'success' => false, 'status' => 404], 404);
    }

    /**
     * @param $ticket
     * @return void
     */
    public function setClientViewedToTrue($ticket): void
    {
        $ticket->client_viewed = 1;
        $ticket->save();
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function destroy($id): JsonResponse
    {
        //Get user ticket details
        $ticket = Ticket::where('id', $id)->where('user_id', Auth::user()->id)->first();

        //Check if the ticket exist
        if (!$ticket) {
            return $this->returnIfNotExist();
        }

        //Delete the ticket
        $ticket->delete();

        return response()->json(['message' => translate('Ticket Deleted successfully'), 'success' => true, 'status' => 200], 200);

    }
}
