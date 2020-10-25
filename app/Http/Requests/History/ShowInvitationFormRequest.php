<?php declare(strict_types=1);

namespace App\Http\Requests\History;

use App\History;
use App\MicroscopePlayer;
use Illuminate\Foundation\Http\FormRequest;

class ShowInvitationFormRequest extends FormRequest
{
    public function authorize()
    {
        /** @var History $history */
        $history = $this->route('history');

        /** @var MicroscopePlayer $user */
        $user = $this->user();

        return $history->public && $user->isGuest();
    }

    public function rules()
    {
        return [];
    }
}
