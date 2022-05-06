<?php

declare(strict_types=1);

/**
 * Copyright (c) 2022 Kai Sassnowski
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @see https://github.com/ksassnowski/utgars-chronicles.app
 */

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
