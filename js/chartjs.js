/*global document */
/*global window */

var chartJS = (function () {

    function degToRad(deg) {
        "use strict";
        return (Math.PI / 180) * deg;
    }

    function radToDeg(rad) {
        "use strict";
        return rad * (180 / Math.PI);
    }

    function degToLen(deg, x, y, r) {
        "use strict";
        return {
            'x': x + r * Math.cos(deg),
            'y': y + r * Math.sin(deg)
        };
    }

    function dist(x1, y1, x2, y2) {
        "use strict";
        return Math.sqrt((x2 - x1) * (x2 - x1) + (y2 - y1) * (y2 - y1));
    }


    function PeaceOfCake(radius, stAngle, endAngle, centX, centY, color) {
        "use strict";
        this.radius = radius;
        this.centX = centX;
        this.centY = centY;
        this.color = color;
        this.stAngle = stAngle;
        this.endAngle = endAngle;

        this.draw = function (canvas) {
            if (canvas.getContext) {
                var c = canvas.getContext('2d');
                c.beginPath();
                c.arc(this.centX, this.centY, this.radius, this.stAngle, this.endAngle);
                c.lineTo(this.centX, this.centY);
                c.fillStyle = this.color;
                c.fill();
                c.closePath();
                this.canvas = canvas;
            }
        };

        this.arcCenterVec = function () {
            var middeg = (endAngle - stAngle) / 2.0;
            return [Math.cos(middeg), Math.sin(middeg)];
        };

        this.clear = function () {
            if (this.canvas) {
                return true;
            }
        };
    }

    function PieChart(elementArray, colorArray, canvas) {
        this.elementArray = elementArray;
        this.colorArray = colorArray;
        this.peacesArray = [];
        this.centX = 400;
        this.centY = 400;
        this.radius = 200;
        this.aniSpeed = 0.01;
        this.canvas = canvas;
        var ob = this;

        this.draw = function () {
            if (canvas.getContext) {
                var c = canvas.getContext('2d'), stAng = 0, endAng = 0, i;
                for (i in this.elementArray) {
                    if (this.elementArray.hasOwnProperty(i)) {
                        endAng = stAng + degToRad(360 * this.elementArray[i]);
                        this.peacesArray[i] = new PeaceOfCake(this.radius, stAng, endAng, this.centX, this.centY, this.colorArray[i]);
                        this.peacesArray[i].draw(canvas);
                        stAng = endAng;
                    }
                }
                this.addLabels();
            }
        };

        this.redraw = function () {
            if (this.canvas.getContext) {
                var c = this.canvas.getContext('2d'),
                    i;
                c.clearRect(0, 0, this.canvas.width, this.canvas.height);
                for (i in this.elementArray) {
                    if (this.elementArray.hasOwnProperty(i)) {
                        this.peacesArray[i].draw(this.canvas);
                    }
                }
                this.addLabels();
            }
        };

        this.addLabels = function (d, ad) {

            d = typeof a !== 'undefined' ? d : 0.2 * this.radius;
            ad = typeof b !== 'undefined' ? ad : 0;

            if (canvas.getContext) {
                var c = canvas.getContext('2d'), i, xy, a, d;
                c.font = "bold 12px sans-serif";
                for (i in this.elementArray) {
                    if (this.elementArray.hasOwnProperty(i)) {
                        if (this.peacesArray[i]) {
                            a = (this.peacesArray[i].stAngle + this.peacesArray[i].endAngle) / 2.0;
                            a += ad;
                            xy = degToLen(a, this.peacesArray[i].centX, this.peacesArray[i].centY, this.peacesArray[i].radius + d);
                            c.font = "bold 24px Verdana";
                            c.fillStyle = "#000000";
                            c.fillText(i, xy['x'], xy['y']);
                        }
                    }
                }
            }
        };

        this.animate = function () {
            var reqAnimFrame = window.requestAnimationFrame ||
                window.webkitRequestAnimationFrame ||
                window.msRequestAnimationFrame ||
                window.oRequestAnimationFrame;
            if (ob.peacesArray[ob.activePeace].radius < 250) {
                reqAnimFrame(ob.animate);
                ob.peacesArray[ob.activePeace].radius += ob.aniSpeed * ob.radius;
                ob.aniSpeed += 0.001;
                ob.redraw();
            } else {
                ob.aniSpeed = 0.001;
            }
        };


        this.init = function () {
            ob.canvas.onclick = function (e) {
                var x = e.pageX - canvas.offsetLeft,
                    y = e.pageY - canvas.offsetTop,
                    chk = (x - ob.centX) * (x - ob.centX) + (y - ob.centY) * (y - ob.centY),
                    v1dl,
                    deg,
                    i;
                if (chk <= ob.radius * ob.radius) {
                    x = x - ob.centX;
                    y = y - ob.centY;
                    v1dl = Math.sqrt(x * x + y * y);
                    deg = Math.acos(x / v1dl);
                    if (y < 0) {
                        deg = degToRad(360) - deg;
                    }
                    for (i in ob.peacesArray) {
                        if (ob.peacesArray.hasOwnProperty(i)) {
                            if (deg >= ob.peacesArray[i].stAngle && deg <= ob.peacesArray[i].endAngle) {
                                if (ob.activePeace !== i) {
                                    if (ob.activePeace) {
                                        ob.peacesArray[ob.activePeace].radius = ob.radius;
                                    }
                                    ob.activePeace = i;
                                    ob.animate();
                                }

                            }
                        }
                    }
                } else {
                    if (ob.activePeace) {
                        ob.peacesArray[ob.activePeace].radius = ob.radius;
                        ob.redraw();
                        delete ob.activePeace;
                    }
                }
            };

            ob.canvas.addEventListener('mousemove', ob.canvas.onclick, false);
        };
        this.init();
    }

    return {
        PieChart: function (elementArray, colorArray, canvas) {
            return new PieChart(elementArray, colorArray, canvas);
        }
    };
}());




