'Test run'
clf
numt1 = [0 0 242]
dent1 = [1 14 215.42]
'T(s1)'
T1 = tf(numt1, dent1)
grid on
step(T1)
title('Step response for T1')
[y1, t1] = step(T1)

numt2 = [0 0 361]
dent2 = [1 4 300];

'T2(s)'
T2 = tf(numt2, dent2)
[y2, t2] = steP(T2);

numt3 = [0 0 362];
dent3 = [1 16 361];
'T3(s)'
T3 = tf(numt3, dent3)
[y3, t3] = step(T3);

clf
grid on
plot(t1, y1, t3, y3)
title('Step responses for T1(s) and T2(s)')
xlabel('Time(ms)')
ylabel('Normalised response')
pause
step(T1, T3)