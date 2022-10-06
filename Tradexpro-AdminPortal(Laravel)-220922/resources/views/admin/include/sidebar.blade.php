<div class="sidebar">
    <!-- logo -->
    <div class="logo">
        <a href="{{route('adminDashboard')}}">
            <img src="{{show_image(Auth::user()->id,'logo')}}" class="img-fluid" alt="">
        </a>
    </div><!-- /logo -->

    <!-- sidebar menu -->
    <div class="sidebar-menu">
        <nav>
            <ul id="metismenu">
                <li class="@if(isset($menu) && $menu == 'dashboard') active-page @endif">
                    <a href="{{route('adminDashboard')}}">
                        <span class="icon"><img src="{{asset('assets/admin/images/sidebar-icons/dashboard.svg')}}" class="img-fluid" alt=""></span>
                        <span class="name">{{__('Dashboard')}}</span>
                    </a>
                </li>
                <li class="@if(isset($menu) && $menu == 'users') active-page @endif">
                    <a href="#" aria-expanded="true">
                        <span class="icon"><img src="{{asset('assets/admin/images/sidebar-icons/user.svg')}}" class="img-fluid" alt=""></span>
                        <span class="name">{{__('User Management')}}</span>
                    </a>
                    <ul class="@if(isset($menu) && $menu == 'users')  mm-show  @endif">
                        <li class="@if(isset($sub_menu) && $sub_menu == 'user') submenu-active @endif">
                            <a href="{{route('adminUsers')}}">{{__('User')}}</a>
                        </li>
                        <li class="@if(isset($sub_menu) && $sub_menu == 'pending_id') submenu-active @endif">
                            <a href="{{route('adminUserIdVerificationPending')}}">{{__('Kyc Verification')}}</a>
                        </li>
                    </ul>
                </li>
                <li class="@if(isset($menu) && $menu == 'coin') active-page @endif">
                    <a href="#">
                        <span class="icon"><img src="{{asset('assets/admin/images/sidebar-icons/coin.svg')}}" class="img-fluid" alt=""></span>
                        <span class="name">{{__('Coin ')}}</span>
                    </a>
                    <ul class="@if(isset($menu) && $menu == 'coin')  mm-show  @endif">
                        <li class="@if(isset($sub_menu) && $sub_menu == 'coin_list') submenu-active @endif">
                            <a href="{{route('adminCoinList')}}">{{__('Coin List')}}</a>
                        </li>
                        <ul class="@if(isset($menu) && $menu == 'coin')  mm-show  @endif">
                            <li class="@if(isset($sub_menu) && $sub_menu == 'coin_pair') submenu-active @endif">
                                <a href="{{route('coinPairs')}}">{{__('Coin Pairs')}}</a>
                            </li>
                        </ul>
                    </ul>
                </li>
                <li class="@if(isset($menu) && $menu == 'wallet') active-page @endif">
                    <a href="#" aria-expanded="true">
                        <span class="icon"><img src="{{asset('assets/admin/images/sidebar-icons/wallet.svg')}}" class="img-fluid" alt=""></span>
                        <span class="name">{{__('User Wallet')}}</span>
                    </a>
                    <ul class="@if(isset($menu) && $menu == 'wallet')  mm-show  @endif">
                        <li class="@if(isset($sub_menu) && $sub_menu == 'personal') submenu-active @endif">
                            <a href="{{route('adminWalletList')}}">{{__('Wallet List')}}</a>
                        </li>
                        <li class="@if(isset($sub_menu) && $sub_menu == 'send_wallet') submenu-active @endif">
                            <a href="{{route('adminSendWallet')}}">{{__('Send Wallet Coin')}}</a>
                        </li>
                        <li class="@if(isset($sub_menu) && $sub_menu == 'send_coin_list') submenu-active @endif">
                            <a href="{{route('adminWalletSendList')}}">{{__('Send Coin History')}}</a>
                        </li>
                    </ul>
                </li>
                <li class="@if(isset($menu) && $menu == 'transaction') active-page @endif">
                    <a href="#" aria-expanded="true">
                        <span class="icon"><img src="{{asset('assets/admin/images/sidebar-icons/Transaction-1.svg')}}" class="img-fluid" alt=""></span>
                        <span class="name">{{__('Deposit/Withdrawal')}}</span>
                    </a>
                    <ul class="@if(isset($menu) && $menu == 'transaction')  mm-show  @endif">
                        <li class="@if(isset($sub_menu) && $sub_menu == 'transaction_all') submenu-active @endif">
                            <a href="{{route('adminTransactionHistory')}}">{{__('All Transaction')}}</a>
                        </li>
                        <li class="@if(isset($sub_menu) && $sub_menu == 'transaction_withdrawal') submenu-active @endif">
                            <a href="{{route('adminPendingWithdrawal')}}">{{__('Pending Withdrawal')}}</a>
                        </li>
                    </ul>
                </li>

                <li class="@if(isset($menu) && $menu == 'profile') active-page @endif">
                    <a href="{{ route('adminProfile') }}">
                        <span class="icon"><img src="{{asset('assets/admin/images/sidebar-icons/profile.svg')}}" class="img-fluid" alt=""></span>
                        <span class="name">{{__('Profile')}}</span>
                    </a>
                </li>
                <li class="@if(isset($menu) && $menu == 'trade') active-page @endif">
                    <a href="#" aria-expanded="true">
                        <span class="icon"><img src="{{asset('assets/admin/images/sidebar-icons/Transaction-1.svg')}}" class="img-fluid" alt=""></span>
                        <span class="name">{{__('Trade Reports')}}</span>
                    </a>
                    <ul class="@if(isset($menu) && $menu == 'trade')  mm-show  @endif">
                        <li class="@if(isset($sub_menu) && $sub_menu == 'buy_order') submenu-active @endif">
                            <a href="{{route('adminAllOrdersHistoryBuy')}}">{{__('Buy Order History')}}</a>
                        </li>
                        <li class="@if(isset($sub_menu) && $sub_menu == 'sell_order') submenu-active @endif">
                            <a href="{{route('adminAllOrdersHistorySell')}}">{{__('Sell Order History')}}</a>
                        </li>
                        <li class="@if(isset($sub_menu) && $sub_menu == 'stop_limit') submenu-active @endif">
                            <a href="{{route('adminAllOrdersHistoryStopLimit')}}">{{__('Stop Limit Order History')}}</a>
                        </li>
                        <li class="@if(isset($sub_menu) && $sub_menu == 'transaction') submenu-active @endif">
                            <a href="{{route('adminAllTransactionHistory')}}">{{__('Transaction History')}}</a>
                        </li>
                    </ul>
                </li>
                <li class="@if(isset($menu) && $menu == 'deposit') active-page @endif">
                    <a href="#" aria-expanded="true">
                        <span class="icon"><img src="{{asset('assets/admin/images/sidebar-icons/Transaction-1.svg')}}" class="img-fluid" alt=""></span>
                        <span class="name">{{__('Deposit')}}</span>
                    </a>
                    <ul class="@if(isset($menu) && $menu == 'deposit')  mm-show  @endif">
                        <li class="@if(isset($sub_menu) && $sub_menu == 'pending') submenu-active @endif">
                            <a href="{{route('adminPendingDepositHistory')}}">{{__('Pending Deposit Report')}}</a>
                        </li>
                        <li class="@if(isset($sub_menu) && $sub_menu == 'gas') submenu-active @endif">
                            <a href="{{route('adminGasSendHistory')}}">{{__('Gas Sent Report')}}</a>
                        </li>
                        <li class="@if(isset($sub_menu) && $sub_menu == 'token') submenu-active @endif">
                            <a href="{{route('adminTokenReceiveHistory')}}">{{__('Token Receive Report')}}</a>
                        </li>
                    </ul>
                </li>
                <li class="@if(isset($menu) && $menu == 'setting') active-page @endif">
                    <a href="#" aria-expanded="true">
                        <span class="icon"><img src="{{asset('assets/admin/images/sidebar-icons/settings.svg')}}" class="img-fluid" alt=""></span>
                        <span class="name">{{__('Settings')}}</span>
                    </a>
                    <ul class="@if(isset($menu) && $menu == 'setting')  mm-show  @endif">
                        <li class="@if(isset($sub_menu) && $sub_menu == 'general') submenu-active @endif">
                            <a href="{{route('adminSettings')}}">{{__('General Settings')}}</a>
                        </li>
                        <li class="@if(isset($sub_menu) && $sub_menu == 'api_settings') submenu-active @endif">
                            <a href="{{route('adminCoinApiSettings')}}">{{__('Api Settings')}}</a>
                        </li>
                        <li class="@if(isset($sub_menu) && $sub_menu == 'currency_list') submenu-active @endif">
                            <a href="{{route('adminCurrencyList')}}">{{__('Fiat Currency')}}</a>
                        </li>
                        <li class="@if(isset($sub_menu) && $sub_menu == 'trade_fees_settings') submenu-active @endif">
                            <a href="{{route('tradeFeesSettings')}}">{{__('Trade Fees Settings')}}</a>
                        </li>
                        <li class="@if(isset($sub_menu) && $sub_menu == 'custom_pages') submenu-active @endif">
                            <a href="{{ route('adminCustomPageList') }}">{{__('Custom Pages')}}</a>
                        </li>
                        <li class="@if(isset($sub_menu) && $sub_menu == 'banner') submenu-active @endif">
                            <a href="{{ route('adminBannerList') }}">{{__('Landing Banner')}}</a>
                        </li>
                        <li class="@if(isset($sub_menu) && $sub_menu == 'feature') submenu-active @endif">
                            <a href="{{ route('adminFeatureList') }}">{{__('Landing Feature')}}</a>
                        </li>
                        <li class="@if(isset($sub_menu) && $sub_menu == 'media') submenu-active @endif">
                            <a href="{{ route('adminSocialMediaList') }}">{{__('Social Media')}}</a>
                        </li>
                        <li class="@if(isset($sub_menu) && $sub_menu == 'announcement') submenu-active @endif">
                            <a href="{{ route('adminAnnouncementList') }}">{{__('Landing Announcement')}}</a>
                        </li>
                        <li class="@if(isset($sub_menu) && $sub_menu == 'landing') submenu-active @endif">
                            <a href="{{ route('adminLandingSetting') }}">{{__('Landing Settings')}}</a>
                        </li>
                        <li class="@if(isset($sub_menu) && $sub_menu == 'config') submenu-active @endif">
                            <a href="{{ route('adminConfiguration') }}">{{__('Configuration')}}</a>
                        </li>
                        <li class="@if(isset($sub_menu) && $sub_menu == 'cookie') submenu-active @endif">
                            <a href="{{ route('adminCookieSettings') }}">{{__('Cookie Settings')}}</a>
                        </li>
                        <li class="@if(isset($sub_menu) && $sub_menu == 'chat') submenu-active @endif">
                            <a href="{{ route('adminChatSettings') }}">{{__('Chat api Settings')}}</a>
                        </li>
                    </ul>
                </li>
                <li class="@if(isset($menu) && $menu == 'notification') active-page @endif">
                    <a href="#" aria-expanded="true">
                        <span class="icon"><img src="{{asset('assets/admin/images/sidebar-icons/Notification.svg')}}" class="img-fluid" alt=""></span>
                        <span class="name">{{__('Notification')}}</span>
                    </a>
                    <ul class="@if(isset($menu) && $menu == 'notification')  mm-show  @endif">
                        <li class="@if(isset($sub_menu) && $sub_menu == 'notify') submenu-active @endif">
                            <a href="{{route('sendNotification')}}">{{__('Notification')}}</a>
                        </li>
                        <li class="@if(isset($sub_menu) && $sub_menu == 'email') submenu-active @endif">
                            <a href="{{route('sendEmail')}}">{{__('Bulk Email')}}</a>
                        </li>
                    </ul>
                </li>
                <li class="@if(isset($menu) && $menu == 'faq') active-page @endif">
                    <a href="{{ route('adminFaqList') }}">
                        <span class="icon"><img src="{{asset('assets/admin/images/sidebar-icons/FAQ.svg')}}" class="img-fluid" alt=""></span>
                        <span class="name">{{__('FAQs')}}</span>
                    </a>
                </li>
                <li class="@if(isset($menu) && $menu == 'log') active-page @endif">
                    <a href="{{ route('adminLogs') }}" target="_blank">
                        <span class="icon"><img src="{{asset('assets/admin/images/sidebar-icons/Transaction-1.svg')}}" class="img-fluid" alt=""></span>
                        <span class="name">{{__('Logs')}}</span>
                    </a>
                </li>
            </ul>
        </nav>
    </div><!-- /sidebar menu -->

</div>
