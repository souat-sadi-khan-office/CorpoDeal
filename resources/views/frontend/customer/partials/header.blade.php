<div class="row ac-header">
    <div class="col-md-6">
        <div class="left">
            <span class="avatar">
                @if (Auth::guard('customer')->user()->avatar == 'default.png')
                    <img src="{{ asset('pictures/user.png') }}" width="80" height="80"
                         alt="{{ Auth::guard('customer')->user()->name }} Photo">
                @else
                    @if (Auth::guard('customer')->user()->provider_name == 'google' || Auth::guard('customer')->user()->provider_name == 'facebook')
                        <img src="{{ Auth::guard('customer')->user()->avatar }}" width="80" height="80"
                             alt="{{ Auth::guard('customer')->user()->name }} Photo">
                    @else
                        <img src="{{ asset(Auth::guard('customer')->user()->avatar) }}" width="80" height="80"
                             alt="{{ Auth::guard('customer')->user()->name }} Photo">
                    @endif
                @endif
            </span>
            <div class="name">
                <p>Hello,</p>
                <p class="user">
                {{ Auth::guard('customer')->user()->name }}

                @if (Auth::guard('customer')->user()->is_premium == 1)
                    <div class="d-flex flex-row gap-2 premium">
                        <i class="fas fa-crown pt-1"></i>
                        <div class="a2">Premium</div>

                        <div class="fireworks">
                            <div class="firework">
                                <div class="explosion">
                                    <div class="spark gold"></div>
                                </div>
                                <div class="explosion">
                                    <div class="spark gold"></div>
                                </div>
                                <div class="explosion">
                                    <div class="spark gold"></div>
                                </div>
                                <div class="explosion">
                                    <div class="spark gold"></div>
                                </div>
                                <div class="explosion">
                                    <div class="spark gold"></div>
                                </div>
                                <div class="explosion">
                                    <div class="spark gold"></div>
                                </div>
                                <div class="explosion">
                                    <div class="spark gold"></div>
                                </div>
                                <div class="explosion">
                                    <div class="spark gold"></div>
                                </div>
                            </div>
                            <div class="firework" style="margin-top: -70px">
                                <div class="explosion">
                                    <div class="spark silver"></div>
                                </div>
                                <div class="explosion">
                                    <div class="spark silver"></div>
                                </div>
                                <div class="explosion">
                                    <div class="spark silver"></div>
                                </div>
                                <div class="explosion">
                                    <div class="spark silver"></div>
                                </div>
                                <div class="explosion">
                                    <div class="spark silver"></div>
                                </div>
                                <div class="explosion">
                                    <div class="spark silver"></div>
                                </div>
                                <div class="explosion">
                                    <div class="spark silver"></div>
                                </div>
                                <div class="explosion">
                                    <div class="spark silver"></div>
                                </div>
                            </div>
                            <div class="firework">
                                <div class="explosion">
                                    <div class="spark gold"></div>
                                </div>
                                <div class="explosion">
                                    <div class="spark gold"></div>
                                </div>
                                <div class="explosion">
                                    <div class="spark gold"></div>
                                </div>
                                <div class="explosion">
                                    <div class="spark gold"></div>
                                </div>
                                <div class="explosion">
                                    <div class="spark gold"></div>
                                </div>
                                <div class="explosion">
                                    <div class="spark gold"></div>
                                </div>
                                <div class="explosion">
                                    <div class="spark gold"></div>
                                </div>
                                <div class="explosion">
                                    <div class="spark gold"></div>
                                </div>
                            </div>
                            <div class="firework" style="margin-top: 70px">
                                <div class="explosion">
                                    <div class="spark silver"></div>
                                </div>
                                <div class="explosion">
                                    <div class="spark silver"></div>
                                </div>
                                <div class="explosion">
                                    <div class="spark silver"></div>
                                </div>
                                <div class="explosion">
                                    <div class="spark silver"></div>
                                </div>
                                <div class="explosion">
                                    <div class="spark silver"></div>
                                </div>
                                <div class="explosion">
                                    <div class="spark silver"></div>
                                </div>
                                <div class="explosion">
                                    <div class="spark silver"></div>
                                </div>
                                <div class="explosion">
                                    <div class="spark silver"></div>
                                </div>
                            </div>
                            <div class="firework">
                                <div class="explosion">
                                    <div class="spark gold"></div>
                                </div>
                                <div class="explosion">
                                    <div class="spark gold"></div>
                                </div>
                                <div class="explosion">
                                    <div class="spark gold"></div>
                                </div>
                                <div class="explosion">
                                    <div class="spark gold"></div>
                                </div>
                                <div class="explosion">
                                    <div class="spark gold"></div>
                                </div>
                                <div class="explosion">
                                    <div class="spark gold"></div>
                                </div>
                                <div class="explosion">
                                    <div class="spark gold"></div>
                                </div>
                                <div class="explosion">
                                    <div class="spark gold"></div>
                                </div>
                            </div>
                            <div class="firework" style="margin-top: -70px">
                                <div class="explosion">
                                    <div class="spark silver"></div>
                                </div>
                                <div class="explosion">
                                    <div class="spark silver"></div>
                                </div>
                                <div class="explosion">
                                    <div class="spark silver"></div>
                                </div>
                                <div class="explosion">
                                    <div class="spark silver"></div>
                                </div>
                                <div class="explosion">
                                    <div class="spark silver"></div>
                                </div>
                                <div class="explosion">
                                    <div class="spark silver"></div>
                                </div>
                                <div class="explosion">
                                    <div class="spark silver"></div>
                                </div>
                                <div class="explosion">
                                    <div class="spark silver"></div>
                                </div>
                            </div>
                            <div class="firework">
                                <div class="explosion">
                                    <div class="spark gold"></div>
                                </div>
                                <div class="explosion">
                                    <div class="spark gold"></div>
                                </div>
                                <div class="explosion">
                                    <div class="spark gold"></div>
                                </div>
                                <div class="explosion">
                                    <div class="spark gold"></div>
                                </div>
                                <div class="explosion">
                                    <div class="spark gold"></div>
                                </div>
                                <div class="explosion">
                                    <div class="spark gold"></div>
                                </div>
                                <div class="explosion">
                                    <div class="spark gold"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    </p>
            </div>
            @if(Auth::guard('customer')->user()->email_verified_at)
                <div class="icon-verified mx-2" style="height: 22px;width: 20px;display: block;">
                    <svg viewBox="0 0 24 24">
                        <g>
                            <path style="fill: var(--primary-color)"
                                  d="M22.25 12c0-1.43-.88-2.67-2.19-3.34.46-1.39.2-2.9-.81-3.91s-2.52-1.27-3.91-.81c-.66-1.31-1.91-2.19-3.34-2.19s-2.67.88-3.33 2.19c-1.4-.46-2.91-.2-3.92.81s-1.26 2.52-.8 3.91c-1.31.67-2.2 1.91-2.2 3.34s.89 2.67 2.2 3.34c-.46 1.39-.21 2.9.8 3.91s2.52 1.26 3.91.81c.67 1.31 1.91 2.19 3.34 2.19s2.68-.88 3.34-2.19c1.39.45 2.9.2 3.91-.81s1.27-2.52.81-3.91c1.31-.67 2.19-1.91 2.19-3.34zm-11.71 4.2L6.8 12.46l1.41-1.42 2.26 2.26 4.8-5.23 1.47 1.36-6.2 6.77z"></path>
                        </g>
                    </svg>
                </div>
            @endif
        </div>
    </div>
    <div class="col-md-6">
        <div class="right">
            <div class="balance">
                <span class="blurb">Star Points</span>
                <span class="amount">{{ Auth::guard('customer')->user()->points }}</span>
            </div>
            <div class="balance">
                <span class="blurb">Negative Balance</span>
                <span class="amount">{{negative_balance()}} {{session()->get('currency_code')}}</span>
                <span style="font-size: 50%">Selected Currency</span>
            </div>
        </div>
    </div>
</div>
