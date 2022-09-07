<?php

use App\Models\UserWallet;

class WalletRepository {

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

    }
}
