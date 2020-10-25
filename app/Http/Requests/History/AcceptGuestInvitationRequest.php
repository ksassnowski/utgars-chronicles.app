<?php declare(strict_types=1);

namespace App\Http\Requests\History;

use App\History;
use App\MicroscopePlayer;
use Illuminate\Foundation\Http\FormRequest;

class AcceptGuestInvitationRequest extends FormRequest
{
    public function authorize()
    {
        /** @var History $history */
        $history = $this->route('history');

        /** @var MicroscopePlayer $user */
        $user = $this->user();

        return $history->public && $user->isGuest();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required',
        ];
    }

    public function name(): string
    {
        return $this->post('name');
    }
}
