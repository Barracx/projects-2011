clc

%Required parameters
ms = 427.0;     % mass of quarter car excluding wheel
mw = 40.0;      % mass of wheel
Ks = 19960.0;   % spring constant for suspension system
Cs = 1050.0;    % damper constant for suspension system
Kw = 175500.0;  % spring constant for wheel

%state matrices
A = [0 1 0 0; 
    -Ks/ms -Cs/ms Ks/ms Cs/ms; 
    0 0 0 1; 
    Ks/mw Cs/mw -(Ks+Kw)/mw -Cs/mw];
B = [0;
    -1/ms; 
    0; 
    1/mw];
C = [0 0 1 0];
D = 0;

%check controllability
rank(ctrb(A,B))
%check observability
rank(obsv(A,C))

%It is required that the system should settle in 0.5 seconds with an
%overshoot of less than 5 %
p1 = -22+10i;
p2 = -22-10i;
p3 = -9-7i;
p4 = -9+7i;
poles = [p1 p2 p3 p4];
Krho = place(A,B,poles); %Find the feddback gain matrix

t = 0:0.01:2;
u = 0.01*ones(size(t));
Nbar=rscale(A,B,C,0,Krho)
lsim(A-B*Krho,B*Nbar,C,0,u,t)


