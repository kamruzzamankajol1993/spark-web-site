@extends('front.master.master')

@section('title')
PC Builder | {{$front_ins_name}}
@endsection
@section('css')

@endsection
@section('body')
 <main>
        <section class="section">
            <div class="spark_container">
                <div class="spark_pcbuild_main_wrapper">
                    <!-- Top Stats Area -->
                    <div class="spark_pcbuild_builder_header">
                        <div class="spark_pcbuild_title_area">
                            <h1>PC Builder - Build Your Own Computer - Spark Tech</h1>
                        </div>

                        <div class="spark_pcbuild_stats_area">
                            <!-- Replaced Wattage Box with PDF Download Button -->
                            <button class="spark_pcbuild_btn_pdf" onclick="window.print()">
                                <i class="fa-solid fa-file-pdf"></i>
                                <span>Get Quote (PDF)</span>
                            </button>
                            <div class="spark_pcbuild_summary_box">
                                <span class="spark_pcbuild_total_price">4,250৳</span>
                                <span class="spark_pcbuild_total_items">1 Items</span>
                            </div>
                        </div>
                    </div>

                    <!-- Section 1: Core Components -->
                    <div class="spark_pcbuild_section_bar">Core Components</div>

                    <!-- 1. CPU (Selected Example) -->
                    <div class="spark_pcbuild_component_row">
                        <div class="spark_pcbuild_icon_col">
                            <img src="https://via.placeholder.com/45" class="spark_pcbuild_img_selected" alt="CPU">
                        </div>
                        <div class="spark_pcbuild_info_col">
                            <div class="spark_pcbuild_category_name">
                                CPU <span class="spark_pcbuild_badge_required">Required</span>
                            </div>
                            <div class="spark_pcbuild_product_name">AMD Athlon PRO 300GE AM4 Socket Desktop Processor
                                with
                                Radeon Vega 3 Graphics (Refurb)</div>
                            <div class="spark_pcbuild_product_specs">
                                <i class="fa-solid fa-circle-dot"></i> 4W - 35W
                            </div>
                        </div>
                        <div class="spark_pcbuild_price_col">4,250৳</div>
                        <div class="spark_pcbuild_action_col">
                            <i class="fa-solid fa-xmark spark_pcbuild_icon_action"></i>
                            <i class="fa-solid fa-rotate spark_pcbuild_icon_action"></i>
                        </div>
                    </div>

                    <!-- 2. Motherboard -->
                    <div class="spark_pcbuild_component_row">
                        <div class="spark_pcbuild_icon_col"><i class="fa-solid fa-microchip"></i></div>
                        <div class="spark_pcbuild_info_col">
                            <div class="spark_pcbuild_category_name">Motherboard <span
                                    class="spark_pcbuild_badge_required">Required</span></div>
                            <div class="spark_pcbuild_placeholder_line"></div>
                        </div>
                        <div class="spark_pcbuild_price_col"></div>
                        <div class="spark_pcbuild_action_col"><button
                                class="btn spark_pcbuild_btn_choose">Choose</button>
                        </div>
                    </div>

                    <!-- 3. RAM -->
                    <div class="spark_pcbuild_component_row">
                        <div class="spark_pcbuild_icon_col"><i class="fa-solid fa-memory"></i></div>
                        <div class="spark_pcbuild_info_col">
                            <div class="spark_pcbuild_category_name">RAM <span
                                    class="spark_pcbuild_badge_required">Required</span></div>
                            <div class="spark_pcbuild_placeholder_line"></div>
                        </div>
                        <div class="spark_pcbuild_price_col"></div>
                        <div class="spark_pcbuild_action_col"><button
                                class="btn spark_pcbuild_btn_choose">Choose</button>
                        </div>
                    </div>

                    <!-- 4. Storage -->
                    <div class="spark_pcbuild_component_row">
                        <div class="spark_pcbuild_icon_col"><i class="fa-solid fa-hard-drive"></i></div>
                        <div class="spark_pcbuild_info_col">
                            <div class="spark_pcbuild_category_name">Storage <span
                                    class="spark_pcbuild_badge_required">Required</span></div>
                            <div class="spark_pcbuild_placeholder_line"></div>
                        </div>
                        <div class="spark_pcbuild_price_col"></div>
                        <div class="spark_pcbuild_action_col"><button
                                class="btn spark_pcbuild_btn_choose">Choose</button>
                        </div>
                    </div>

                    <!-- 5. Graphics Card -->
                    <div class="spark_pcbuild_component_row">
                        <div class="spark_pcbuild_icon_col"><i class="fa-solid fa-vr-cardboard"></i></div>
                        <div class="spark_pcbuild_info_col">
                            <div class="spark_pcbuild_category_name">Graphics Card</div>
                            <div class="spark_pcbuild_placeholder_line"></div>
                        </div>
                        <div class="spark_pcbuild_price_col"></div>
                        <div class="spark_pcbuild_action_col"><button
                                class="btn spark_pcbuild_btn_choose">Choose</button>
                        </div>
                    </div>

                    <!-- 6. Power Supply -->
                    <div class="spark_pcbuild_component_row">
                        <div class="spark_pcbuild_icon_col"><i class="fa-solid fa-plug"></i></div>
                        <div class="spark_pcbuild_info_col">
                            <div class="spark_pcbuild_category_name">Power Supply</div>
                            <div class="spark_pcbuild_placeholder_line"></div>
                        </div>
                        <div class="spark_pcbuild_price_col"></div>
                        <div class="spark_pcbuild_action_col"><button
                                class="btn spark_pcbuild_btn_choose">Choose</button>
                        </div>
                    </div>

                    <!-- 7. Casing -->
                    <div class="spark_pcbuild_component_row">
                        <div class="spark_pcbuild_icon_col"><i class="fa-solid fa-box"></i></div>
                        <div class="spark_pcbuild_info_col">
                            <div class="spark_pcbuild_category_name">Casing</div>
                            <div class="spark_pcbuild_placeholder_line"></div>
                        </div>
                        <div class="spark_pcbuild_price_col"></div>
                        <div class="spark_pcbuild_action_col"><button
                                class="btn spark_pcbuild_btn_choose">Choose</button>
                        </div>
                    </div>

                    <!-- Section 2: Peripherals & Others -->
                    <div class="spark_pcbuild_section_bar">Peripherals & Others</div>

                    <!-- 8. Monitor -->
                    <div class="spark_pcbuild_component_row">
                        <div class="spark_pcbuild_icon_col"><i class="fa-solid fa-desktop"></i></div>
                        <div class="spark_pcbuild_info_col">
                            <div class="spark_pcbuild_category_name">Monitor</div>
                            <div class="spark_pcbuild_placeholder_line"></div>
                        </div>
                        <div class="spark_pcbuild_price_col"></div>
                        <div class="spark_pcbuild_action_col"><button
                                class="btn spark_pcbuild_btn_choose">Choose</button>
                        </div>
                    </div>

                    <!-- 9. Casing Cooler -->
                    <div class="spark_pcbuild_component_row">
                        <div class="spark_pcbuild_icon_col"><i class="fa-solid fa-fan"></i></div>
                        <div class="spark_pcbuild_info_col">
                            <div class="spark_pcbuild_category_name">Casing Cooler</div>
                            <div class="spark_pcbuild_placeholder_line"></div>
                        </div>
                        <div class="spark_pcbuild_price_col"></div>
                        <div class="spark_pcbuild_action_col"><button
                                class="btn spark_pcbuild_btn_choose">Choose</button>
                        </div>
                    </div>

                    <!-- 10. Keyboard -->
                    <div class="spark_pcbuild_component_row">
                        <div class="spark_pcbuild_icon_col"><i class="fa-solid fa-keyboard"></i></div>
                        <div class="spark_pcbuild_info_col">
                            <div class="spark_pcbuild_category_name">Keyboard</div>
                            <div class="spark_pcbuild_placeholder_line"></div>
                        </div>
                        <div class="spark_pcbuild_price_col"></div>
                        <div class="spark_pcbuild_action_col"><button
                                class="btn spark_pcbuild_btn_choose">Choose</button>
                        </div>
                    </div>

                    <!-- 11. Mouse -->
                    <div class="spark_pcbuild_component_row">
                        <div class="spark_pcbuild_icon_col"><i class="fa-solid fa-mouse"></i></div>
                        <div class="spark_pcbuild_info_col">
                            <div class="spark_pcbuild_category_name">Mouse</div>
                            <div class="spark_pcbuild_placeholder_line"></div>
                        </div>
                        <div class="spark_pcbuild_price_col"></div>
                        <div class="spark_pcbuild_action_col"><button
                                class="btn spark_pcbuild_btn_choose">Choose</button>
                        </div>
                    </div>

                    <!-- 12. Speaker & Home Theater -->
                    <div class="spark_pcbuild_component_row">
                        <div class="spark_pcbuild_icon_col"><i class="fa-solid fa-volume-high"></i></div>
                        <div class="spark_pcbuild_info_col">
                            <div class="spark_pcbuild_category_name">Speaker & Home Theater</div>
                            <div class="spark_pcbuild_placeholder_line"></div>
                        </div>
                        <div class="spark_pcbuild_price_col"></div>
                        <div class="spark_pcbuild_action_col"><button
                                class="btn spark_pcbuild_btn_choose">Choose</button>
                        </div>
                    </div>

                    <!-- 13. Headphone -->
                    <div class="spark_pcbuild_component_row">
                        <div class="spark_pcbuild_icon_col"><i class="fa-solid fa-headphones"></i></div>
                        <div class="spark_pcbuild_info_col">
                            <div class="spark_pcbuild_category_name">Headphone</div>
                            <div class="spark_pcbuild_placeholder_line"></div>
                        </div>
                        <div class="spark_pcbuild_price_col"></div>
                        <div class="spark_pcbuild_action_col"><button
                                class="btn spark_pcbuild_btn_choose">Choose</button>
                        </div>
                    </div>

                    <!-- 14. Wifi Adapter / LAN Card -->
                    <div class="spark_pcbuild_component_row">
                        <div class="spark_pcbuild_icon_col"><i class="fa-solid fa-wifi"></i></div>
                        <div class="spark_pcbuild_info_col">
                            <div class="spark_pcbuild_category_name">Wifi Adapter / LAN Card</div>
                            <div class="spark_pcbuild_placeholder_line"></div>
                        </div>
                        <div class="spark_pcbuild_price_col"></div>
                        <div class="spark_pcbuild_action_col"><button
                                class="btn spark_pcbuild_btn_choose">Choose</button>
                        </div>
                    </div>

                    <!-- 15. Anti Virus -->
                    <div class="spark_pcbuild_component_row">
                        <div class="spark_pcbuild_icon_col"><i class="fa-solid fa-shield-halved"></i></div>
                        <div class="spark_pcbuild_info_col">
                            <div class="spark_pcbuild_category_name">Anti Virus</div>
                            <div class="spark_pcbuild_placeholder_line"></div>
                        </div>
                        <div class="spark_pcbuild_price_col"></div>
                        <div class="spark_pcbuild_action_col"><button
                                class="btn spark_pcbuild_btn_choose">Choose</button>
                        </div>
                    </div>

                    <!-- 16. UPS -->
                    <div class="spark_pcbuild_component_row">
                        <div class="spark_pcbuild_icon_col"><i class="fa-solid fa-car-battery"></i></div>
                        <div class="spark_pcbuild_info_col">
                            <div class="spark_pcbuild_category_name">UPS</div>
                            <div class="spark_pcbuild_placeholder_line"></div>
                        </div>
                        <div class="spark_pcbuild_price_col"></div>
                        <div class="spark_pcbuild_action_col"><button
                                class="btn spark_pcbuild_btn_choose">Choose</button>
                        </div>
                    </div>

                </div>
            </div>
        </section>

    </main>
@endsection
@section('script')
@endsection