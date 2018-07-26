<?php

namespace App\Http\Controllers\Front;

use App\Http\Requests\ContactRequest;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;

/**
 * Class ContactC
 * @package App\Http\Controllers\Front
 */
class ContactC extends Controller
{
    /**
     * Show the form
     *
     * @return View
     */
    public function showForm()
    {

        \View::share('meta',  [
            'h1' => 'Контакты',
            'title' => 'Контакты',
            'desc' => 'Контакты',
            'key' => 'Контакты'
        ]);

        return view('front.page.contact');
    }

    /**
     * Email the contact request
     *
     * @param ContactRequest $request
     * @return Redirect
     */
    public function sendContactInfo(ContactRequest $request)
    {

        $data = $request->only('name', 'email', 'phone');
        $data['messageLines'] = explode("\n", $request->get('message'));

        Mail::send('mail.contact', $data, function ($message) use ($data) {
            $message->subject('Вопрос с сайта '. $_SERVER['HTTP_HOST'] .' : '.$data['name'])
                ->to(config('app.email'));
//                ->replyTo($data['email']);
        });

        return back()
            ->withSuccess("Письмо отправлено. Спасибо.");
    }

    /**
     * Show the form
     *
     * @return View
     */
    public function showVozvratForm()
    {
        \View::share('breadcrumbs', [
            ['name' => 'Возврат товара']
        ]);

        \View::share('meta',  [
            'h1' => 'Возврат товара',
            'title' => 'Возврат товара',
            'desc' => 'Интернет магазин обоев, Возврат товара',
            'key' => 'Возврат товара'
        ]);

        \View::share('show_banner', true);
        \View::share('hide_banner_text', true);

        return view('front.pages.vozvrat');
    }

    /**
     * Email the contact request
     *
     * @param ContactRequest $request
     * @return Redirect
     */
    public function sendVozvratInfo(ContactRequest $request)
    {

        $data = $request->only('name', 'email', 'phone', 'url');
        $data['messageLines'] = explode("\n", $request->get('message'));

        Mail::send('mail.vozvrat', $data, function ($message) use ($data) {
            $message->subject('Возврат с сайта '. $_SERVER['HTTP_HOST'] .' : '.$data['name'])
                ->to(config('app.email'));
//                ->replyTo($data['email']);
        });

        return back()
            ->withSuccess("Письмо отправлено. Спасибо.");
    }


}

