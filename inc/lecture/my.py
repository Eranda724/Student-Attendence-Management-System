n = int(input())

arr =[]

p = arr.append("YES")
q = arr.append("NO")
i=1
for i in range(n):
    a,b = map(int, input().split())

    if b%2==0:
        if a==0:
            p
        else:
            if a%2 == 0:
                p
            else:
                q
    else:
        if a==0:
            q
        else:
            if a%2==0:
                p

            else:
                q


for j in range(n-1):
    print(arr[j])