E=170000000000000;
t=0.001;
L1=0.025;
L2=0.040;
L3=0.015;
pc=2330;
w=0.002;
pf=702.4;
fo1=((1/pi)*sqrt(E/pc)*(t/L1^2));
fo2=((1/pi)*sqrt(E/pc)*(t/L2^2));
fo3=((1/pi)*sqrt(E/pc)*(t/L3^2));
f_fluid1=fo1*(1+(pi*pf*w/4*pc*t)*2)^-0.5;
f_fluid2=fo1*(1+(pi*pf*w/4*pc*t)*2)^-0.5;
f_fluid3=fo1*(1+(pi*pf*w/4*pc*t)*2)^-0.5;
wn1=2*pi*f_fluid1;
wn2=2*pi*f_fluid2;
wn3=2*pi*f_fluid3;
num=[0 1];
den1=[1/(wn1)^2 (0.3*2)/wn1 1];
den2=[1/(wn2)^2 (0.5*2)/wn2 1];
den3=[1/(wn3)^2 (0.7*2)/wn3 1];
G1=tf(num,den1);
G2=tf(num,den2);
G3=tf(num,den3); 
step(G1, 'r--',G2, 'b-.' ,G3, 'g');
%bode(G1,G2,G3);
%------------------------------------------------------
%low pass filter bandwidth 100kHz
Filtnum=[0 0.5];
Filtden=[0.000000000004 0.00000056 1];
G4=tf(Filtnum,Filtden);
%bode(G4);
%--------------------------------------------------
%Amplifier
G5=1000;
%system response(amplifier, sensor, filter)
System = G4*G2*G5;
%step(System);
%bode(System);
%-------------------------------------------------------
%Sample the signal 100 times per second, for 2 seconds.
Fs = 50000;
t = [0:2*Fs+1]'/Fs;
Fc = 20; % Carrier frequency
x = sin(2*pi*t); % Sinusoidal signal

% Modulate x using single- and double-sideband AM.
ydouble = ammod(x,Fc,Fs);
%plot(ydouble);
%-------------------------------------
%t=0:0.001:0.4;
%vd=1*cos(2*pi*5*t);
%vc=0.5*cos(2*pi*15*t);
%ft=vc.*vd;
%hold on;
%plot(t,vd,'color','r');
%plot(t,vc,'color','b');
%plot(t,am,'color','g'); 
%hold off;
%----------------------------------------------------