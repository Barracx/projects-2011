
clc
%parameters
Mcar = 450;   %mass of the car body
Mw = 40;  %mass of the suspension
m = Mcar+Mw;%total mass of the car
J=1.6; %moment of inertia for the wheel
Cx= 0.856; %
B=0.08;   %
Bs=700;  %
Ks=23500;%
Tmax=10000; %
tsettling=0.5;  %
Kw=190000;  %
Xumax=0.005;    %
r=0.4;          %
g=9.81;         %
Normal=m*g;          %

%initial conditions
v0=120*1000/3600;
w0=v0/r;
mu0=0.8; 
lambda0=0.2;%

%state matrices
A = [0 1 0 0; -Ks/Mcar -Bs/Mcar Ks/Mcar Bs/Mcar; 0 0 0 1; Ks/Mw Bs/Mw -(Ks+Kw)/Mw -Bs/Mw]
B = [0 -1/Mcar 0 1/Mw]'
C = [-1 0 1 0]
D = 0

%check controllability
rank(ctrb(A,B))
%check observability
rank(obsv(A,C))

%put into state space
T = ss(A,B,C,0);
%step(T)

[olz,olp,olg] = ss2zp(A,B,C,D) %open loop zeros and poles

clp = [-20+5j, -20-5j, -8-10j, -8+10j]; %desired pole locations
K = place(A,B,clp) %Find the feddback gain matrix

t = 0:0.01:2;
u = 0.01*ones(size(t));
Nbar=rscale(A,B,C,0,K)
lsim(A-B*K,B*Nbar,C,0,u,t)

% u = Xumax*Kw*ones(size(t));
% Nbar=rscale(A,B,C,0,K)
% lsim(A-B*K,B*Nbar,C,0,u,t)
% %N = 1/dcgain(A-B*K,B,C,D) %Find the feedforward gain
% Ac1 = A-B*K; Bc1 =B; Cc1 = C; Dc1 = D; % closedloop system
% 
% %Tc = ss(Ac1,Bc1*N,Cc1,Dc1);
% step(Ac1,Bc1,Cc1,Dc1)

