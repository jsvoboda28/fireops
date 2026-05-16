<x-filament-widgets::widget>
    <div style="background: linear-gradient(135deg, #1E40AF 0%, #6D28D9 100%); border-radius: 16px; padding: 24px; box-shadow: 0 12px 32px rgba(30, 64, 175, 0.25); color: white; position: relative; overflow: hidden;">
        
        <div style="position: absolute; top: -50px; right: -50px; width: 200px; height: 200px; background: rgba(255,255,255,0.08); border-radius: 50%;"></div>
        <div style="position: absolute; bottom: -80px; left: -80px; width: 250px; height: 250px; background: rgba(255,255,255,0.05); border-radius: 50%;"></div>
        
        <div style="position: relative; display: flex; align-items: center; justify-content: space-between; gap: 20px; flex-wrap: wrap;">
            
            <div style="flex: 1; min-width: 280px;">
                <div style="font-size: 10px; font-weight: 800; text-transform: uppercase; letter-spacing: 2px; opacity: 0.85; margin-bottom: 6px;">
                    🖥 PROFESIONALNI MOD
                </div>
                <div style="font-size: 24px; font-weight: 900; letter-spacing: -0.5px; line-height: 1.2; margin-bottom: 6px;">
                    Operativni mod — 2 ekrana
                </div>
                <div style="font-size: 13px; opacity: 0.9; line-height: 1.5; max-width: 480px;">
                    Otvori dispečerski centar i operativnu kartu odjednom u dva odvojena prozora. 
                    Ako imaš dva monitora, automatski će se rasporediti.
                </div>
            </div>

            <div style="display: flex; gap: 10px; flex-direction: column;">
                <button type="button" id="fireops-otvori-operativni-mod"
                        onclick="
                            (function(){
                                var sirina = window.screen.availWidth;
                                var visina = window.screen.availHeight;
                                var dva = sirina > 2000;
                                var poz1, poz2;
                                if (dva) {
                                    var pol = Math.floor(sirina/2);
                                    poz1 = 'left=0,top=0,width='+pol+',height='+visina;
                                    poz2 = 'left='+pol+',top=0,width='+pol+',height='+visina;
                                } else {
                                    var pol = Math.floor(window.innerWidth/2);
                                    poz1 = 'left=0,top=0,width='+pol+',height='+window.innerHeight;
                                    poz2 = 'left='+pol+',top=0,width='+pol+',height='+window.innerHeight;
                                }
                                var opc = ',resizable=yes,scrollbars=yes,status=yes,menubar=no,toolbar=no,location=no';
                                var w1 = window.open('/admin/operativa', 'fireops_karta', poz1+opc);
                                setTimeout(function(){
                                    var w2 = window.open('/admin/dispatcher', 'fireops_centar', poz2+opc);
                                    if(!w1 || !w2){ alert('Browser je blokirao pop-up prozore. Dozvoli pop-up za ovu stranicu (ikona u adresnoj traci).'); }
                                }, 300);
                            })();
                        "
                        style="background: white; color: #1E40AF; border: none; padding: 16px 28px; border-radius: 12px; font-size: 15px; font-weight: 900; cursor: pointer; box-shadow: 0 4px 14px rgba(0,0,0,0.15); display: flex; align-items: center; gap: 10px; transition: all 0.2s;"
                        onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 20px rgba(0,0,0,0.2)';"
                        onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 14px rgba(0,0,0,0.15)';">
                    🚀 Pokreni operativni mod
                </button>
                
                <div style="display: flex; gap: 8px;">
                    <a href="/admin/dispatcher" target="_blank"
                       style="flex: 1; background: rgba(255,255,255,0.15); color: white; padding: 8px 12px; border-radius: 8px; text-decoration: none; font-size: 11px; font-weight: 700; text-align: center; backdrop-filter: blur(8px);">
                        📋 Samo centar
                    </a>
                    <a href="/admin/operativa" target="_blank"
                       style="flex: 1; background: rgba(255,255,255,0.15); color: white; padding: 8px 12px; border-radius: 8px; text-decoration: none; font-size: 11px; font-weight: 700; text-align: center; backdrop-filter: blur(8px);">
                        🗺 Samo karta
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-filament-widgets::widget>
