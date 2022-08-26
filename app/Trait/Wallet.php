<?php

namespace App\Trait;

use App\Models\UserWallet;

trait Wallet {
    public function createWallet($id)
    {
        $wallet = UserWallet::where('user_id', $id)->first();
        if (!$wallet) {
            $wallet = new UserWallet;
            $wallet->amount = 0;
            $wallet->user_id = $id;
            $wallet->save();

            return $wallet;
        }

        return $wallet;

    }
}
