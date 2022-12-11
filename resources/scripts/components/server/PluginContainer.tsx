import useSWR from 'swr';
import { object, string } from 'yup';
import * as Icon from 'react-feather';
import useFlash from '@/plugins/useFlash';
import { ServerContext } from '@/state/server';
import React, { useEffect, useState } from 'react';
import { Dialog } from '@/components/elements/dialog';
import { Button } from '@/components/elements/button';
import { Field, Form, Formik, FormikHelpers } from 'formik';
import TitledGreyBox from '@/components/elements/TitledGreyBox';
import FlashMessageRender from '@/components/FlashMessageRender';
import getPlugins, { Plugin } from '@/api/server/plugins/getPlugins';
import ServerContentBlock from '@/components/elements/ServerContentBlock';
import installPlugin from '@/api/server/plugins/installPlugin';

interface Values {
    query: string;
}

export default () => {
    const [query, setQuery] = useState('');
    const [open, setOpen] = useState(false);
    const { clearFlashes, addFlash, clearAndAddHttpError } = useFlash();
    const [pluginId, setPluginId] = useState<number>(0);
    const uuid = ServerContext.useStoreState((state) => state.server.data!.uuid);

    const { data, error } = useSWR<Plugin>([uuid, query, '/plugins'], (uuid, query) => getPlugins(uuid, query));

    console.log(data);

    useEffect(() => {
        if (!error) {
            clearFlashes('server:plugins');
        } else {
            clearAndAddHttpError({ key: 'server:plugins', error });
        }
    }, [error]);

    const submit = ({ query }: Values, { setSubmitting }: FormikHelpers<Values>) => {
        setQuery(query);
        setSubmitting(false);
    };

    const doDownload = (id: number) => {
        console.log('Installing plugin with ID ' + id);
        installPlugin(uuid, id)
            .then(() => setOpen(false))
            .then(() =>
                addFlash({
                    key: 'server:plugins',
                    type: 'success',
                    message: 'Plugin installed successfully.',
                })
            )
            .catch((error) => clearAndAddHttpError(error));
    };

    return (
        <ServerContentBlock title={'Plugins'}>
            <FlashMessageRender byKey={'server:plugins'} />
            <h1 className={'j-left text-5xl'}>プラグインインストーラー</h1>
            <h3 className={'j-left text-2xl mt-2 text-neutral-500 mb-10'}>Spigotのプラグインを検索してダウンロードすることができます。</h3>
            <Formik
                onSubmit={submit}
                initialValues={{ query: '' }}
                validationSchema={object().shape({
                    query: string().required(),
                })}
            >
                <Form className={'j-up'}>
                    <div className={'grid grid-cols-12 mb-10'}>
                        <div className={'col-span-11 mr-4'}>
                            <Field
                                name={'query'}
                                placeholder={'検索に入力する...'}
                                className={'p-3 text-sm w-full bg-gray-800 rounded'}
                            />
                        </div>
                        <Button type={'submit'}>
                            探す <Icon.Search size={18} className={'ml-1'} />
                        </Button>
                    </div>
                </Form>
            </Formik>
            <Dialog.Confirm
                open={open}
                onClose={() => setOpen(false)}
                title={'プラグインインストール'}
                onConfirmed={() => doDownload(pluginId)}
            >
                本当にこのプラグインをダウンロードしますか？
            </Dialog.Confirm>
            {!data ? null : (
                <>
                    {!data.plugins ? (
                        <p className={'j-up text-gray-400 text-center'}>検索クエリに入力されるのを待っています。</p>
                    ) : (
                        <>
                            {data.plugins.length < 1 ? (
                                <p>Couldn&apos;t find any plugins.</p>
                            ) : (
                                <div className={'j-up lg:grid lg:grid-cols-3 p-2'}>
                                    {data.plugins.map((plugin, key) => (
                                        <>
                                            <TitledGreyBox title={plugin.name} key={key} className={'m-2'}>
                                                <div className={'lg:grid lg:grid-cols-5'}>
                                                    <div className={'lg:col-span-4'}>
                                                        <p className={'text-sm line-clamp-1'}>{plugin.tag}</p>
                                                        <p className={'text-xs text-gray-400'}>
                                                            {`https://api.spiget.org/v2/resources/${plugin.id}/go`}
                                                        </p>
                                                    </div>
                                                    <div>
                                                        {plugin.premium ? (
                                                            <Button.Text className={'m-1'} disabled>
                                                                <Icon.DownloadCloud size={18} />
                                                            </Button.Text>
                                                        ) : (
                                                            <Button
                                                                className={'m-1'}
                                                                onClick={() => {
                                                                    setPluginId(plugin.id);
                                                                    setOpen(true);
                                                                }}
                                                            >
                                                                <Icon.DownloadCloud size={18} />
                                                            </Button>
                                                        )}
                                                        <a href={`https://api.spiget.org/v2/resources/${plugin.id}/go`}>
                                                            <Button className={'m-1'}>
                                                                <Icon.ExternalLink size={18} />
                                                            </Button>
                                                        </a>
                                                    </div>
                                                </div>
                                            </TitledGreyBox>
                                        </>
                                    ))}
                                </div>
                            )}
                        </>
                    )}
                </>
            )}
        </ServerContentBlock>
    );
};
