#include<stdio.h>
struct student{
    char name[60];
    int id;
    int number;
};
struct student s[2];
int main(){
int i;

for(i=0;i<2;i++){
    printf("Enter your name :");
    scanf("%s",&s[i].name);
        printf("Enter your id :");
    scanf("%d",&s[i].id);
        printf("Enter your number :");
    scanf("%d",&s[i].number);
}
for(i =0;i<2;i++){
    printf("The students name is : %s \n",s[i].name);
    printf("The students id is : %d \n",s[i].id);
    printf("The students number is : %d \n",s[i].number);
}
    return 0;
}