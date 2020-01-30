<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\MailTemplate;
use Illuminate\Http\Request;
use App\Models\MailTemplateInit;
use App\Http\Requests\MailTemplate\StoreRequest;

class MailTemplateController extends Controller
{
    protected $model;

    protected $model_init;

    public function __construct(MailTemplate $model, MailTemplateInit $model_init)
    {
        $this->model      = $model;
        $this->model_init = $model_init;
    }

    /**
     * Returns all mail templates.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function all()
    {
        $local_mails = Setting::getSettingsFor('Email')->pluck('value');
        $templates   = $this->model::all();

        return response()->json([
          'local_mails' => $local_mails,
          'templates'   => $templates,
        ]);
    }

    /**
     * Updates templates data.
     *
     * @param StoreRequest $request
     * @param MailTemplate $template
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(MailTemplate $template, StoreRequest $request)
    {
        $res = $template->update($request->validated());
        if ($res) {
            return response(['success' => 'Template has been successfully updated'], 200);
        }

        return response(['error' => 'Error occurred while updating template record'], 400);
    }

    /**
     * Restoring state of the model by slug.
     *
     * @param string $slug
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function revert(string $slug)
    {
        $init_state = $this->model_init::bySlug($slug);

        $revert = $this->model::updateOrCreate(
            ['slug' => $init_state->slug],
            [
          'name'                   => $init_state->name,
          'from'                   => $init_state->from,
          'to'                     => $init_state->to,
          'copy'                   => $init_state->copy,
          'description'            => $init_state->description,
          'subject'                => $init_state->subject,
          'message'                => $init_state->message,
          'enabled'                => $init_state->enabled,
          'send_copy_to_manager'   => $init_state->send_copy_to_manager,
          'send_copy_to_customers' => $init_state->send_copy_to_customers,
        ]
        );
        if ($revert) {
            return response(['success' => 'Template has been successfully reverted'], 200);
        }

        return response(['error' => 'Error occurred while reverting template record'], 400);
    }
}
