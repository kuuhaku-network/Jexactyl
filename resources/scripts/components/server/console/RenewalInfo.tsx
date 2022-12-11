import React, { useState } from 'react';
import useFlash from '@/plugins/useFlash';
import { httpErrorToHuman } from '@/api/http';
import { useStoreState } from '@/state/hooks';
import { ServerContext } from '@/state/server';
import renewServer from '@/api/server/renewServer';
import { Dialog } from '@/components/elements/dialog';
import SpinnerOverlay from '@/components/elements/SpinnerOverlay';

export default () => {
    const [open, setOpen] = useState(false);
    const { addFlash, clearFlashes } = useFlash();
    const [loading, setLoading] = useState(false);
    const store = useStoreState((state) => state.storefront.data!);
    const uuid = ServerContext.useStoreState((state) => state.server.data!.uuid);
    const renewal = ServerContext.useStoreState((state) => state.server.data!.renewal);

    const doRenewal = () => {
        setLoading(true);
        clearFlashes('console:share');

        renewServer(uuid)
            .then(() => {
                setOpen(false);
                setLoading(false);

                addFlash({
                    key: 'console:share',
                    type: 'success',
                    message: 'サーバーを更新しました。',
                });
            })
            .catch((error) => {
                setOpen(false);
                setLoading(false);

                console.log(httpErrorToHuman(error));
                addFlash({
                    key: 'console:share',
                    type: 'danger',
                    message: 'サーバーを更新できません。クレジットは十分ですか？',
                });
            });
    };

    return (
        <>
            <Dialog.Confirm
                open={open}
                onClose={() => setOpen(false)}
                title={'サーバー更新の確認'}
                onConfirmed={() => doRenewal()}
            >
                <SpinnerOverlay visible={loading} />
                {store.renewals.cost} クレジットが請求され、次回まで {store.renewals.days} 日が追加されます
                更新予定です。
            </Dialog.Confirm>
            in {renewal} days{' '}
            <span className={'text-blue-500 text-sm cursor-pointer'} onClick={() => setOpen(true)}>
                {'('}更新{')'}
            </span>
        </>
    );
};
