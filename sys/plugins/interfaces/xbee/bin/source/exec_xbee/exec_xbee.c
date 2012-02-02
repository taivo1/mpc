#include <errno.h>
#include <fcntl.h>
//#include <iostream.h>
#include <netinet/in.h>
#include <pthread.h>
#include <signal.h>
#include <stdlib.h>
#include <stdio.h>
#include <sys/ipc.h>
#include <sys/sem.h>
#include <sys/socket.h>
#include <sys/time.h>
#include <sys/types.h>
#include <sys/wait.h>
#include <string.h>
#include <time.h>
#include <unistd.h>
#include <termios.h> /* Terminal control library (POSIX) */
#include <ctype.h>


#define MAX 500

int sd=3;
char *serialPort="";
char *serialPort0="/dev/ttyS0";
char *serialPort1="/dev/ttyS1";
char *USBserialPort0="/dev/ttyUSB0";
char *USBserialPort1="/dev/ttyUSB1";
char valor[MAX]="";
char c;
char *val;
int j=0;
struct termios opciones;
int num;
char *s0="S0";
char *s1="S1";
char *u0="USB0";
char *u1="USB1";
int speed = B19200;
int end=0;

// This is just for communication pourpouses only.
int read_status=0;
int write_status=0;
char order[520]=" ";


typedef struct {char *name; int flag; } speed_spec;

speed_spec speeds[] =
	{
		{"0", B1200},
		{"1", B2400},
		{"2", B4800},
		{"3", B9600},
		{"4", B19200},
		{"5", B38400},
		{"6", B57600},
		{"7", B115200},
		{NULL, 0}
	};

// END OF SHARED MEMORY
 
int mygetch(void)
{
struct termios oldt,
newt;
int ch;
tcgetattr( STDIN_FILENO, &oldt );
newt = oldt;
newt.c_lflag &= ~( ICANON | ECHO );
tcsetattr( STDIN_FILENO, TCSANOW, &newt );
ch = getchar();
tcsetattr( STDIN_FILENO, TCSANOW, &oldt );
return ch;
}

void sigchld_handler(int s)
{
    while(wait(NULL) > 0);
}


void * read_serial( void * temp_pt )
{
	/**************************************************
	 * CODE FOR THREAD 2
	 **************************************************/
	while(!end)
	{
		if (read(sd,&c,1)!=0)
		if ((isprint(c)!=0) || (c=='\n'))
		fprintf(stderr,"%c",c);
		//usleep(100000);
	}
    return 0;
}

int main(int argc, char *argv[])
{


    /* STARTING MAIN */
    if (argc < 4)
    {
            fprintf(stderr,"Usage: %s [port] [speed_mode] [order_1] ... [order_n]\nDefault speed_mode is 4 that represents a value of 19200\nAllowed ports: S0 S1 USB0 USB1\nAllowed speed_mode values: 0->1200 1->2400 2->4800 3->9600 4->19200 5->38400 6->57600 7->115200\n", argv[0], argv[0]);
            fprintf(stderr,"Author: Octavio Benedi Sanchez\n");
            exit(0);
    }


    speed_spec *s;
    for(s = speeds; s->name; s++) {
            if(strcmp(s->name, argv[2]) == 0) {
                    speed = s->flag;
                    //fprintf(stderr, "setting speed %s\n", s->name);
                    break;
            }
    }

    if(!strcmp(argv[1],s0))
    {
            //fprintf(stderr,"ttyS0 chosen\n...\n");
            serialPort=serialPort0;
    }
    if(!strcmp(argv[1],s1))
    {
            //fprintf(stderr,"ttyS1 chosen\n...\n");
            serialPort=serialPort1;
    }
    if(!strcmp(argv[1],u0))
    {
            //fprintf(stderr,"ttyUSB0 chosen\n...\n");
            serialPort=USBserialPort0;
    }
    if(!strcmp(argv[1],u1))
    {
            //fprintf(stderr,"ttyUSB1 chosen\n...\n");
            serialPort=USBserialPort1;
    }
    if(!strcmp(serialPort,""))
    {
            //fprintf(stderr,"Choose a valid port (S0, S1, USB0, USB1)\n");
            exit(0);
    }
    //fprintf(stderr,"Press Ctrl+c to close the program\n");
    if ((sd = open(serialPort, O_RDWR | O_NOCTTY | O_NDELAY)) == -1)
    {
            fprintf(stderr,"Unable to open the serial port %s - \n", serialPort);
            exit(-1);
    }
    else
    {
            if (!sd)
            {
            /*Sometimes the first time you call open it does not return the
             * right value (3) of the free file descriptor to use, for this
             * reason you can set manually the sd value to 3 or call again
             * the open function (normally returning 4 to sd), advised!*/
            sd = open(serialPort, O_RDWR | O_NOCTTY | O_NDELAY);
            }
            //fprintf(stderr,"Serial Port open at: %i\n", sd);
            fcntl(sd, F_SETFL, 0);
            //fcntl(sd, F_SETFL, FNDELAY);
    }
    tcgetattr(sd, &opciones);
    cfsetispeed(&opciones, speed);
    cfsetospeed(&opciones, speed);
    opciones.c_cflag |= (CLOCAL | CREAD);
    /*No parity*/
    opciones.c_cflag &= ~PARENB;
    opciones.c_cflag &= ~CSTOPB;
    opciones.c_cflag &= ~CSIZE;
    opciones.c_cflag |= CS8;
    /*raw input:
     * making the applycation ready to receive*/
    opciones.c_lflag &= ~(ICANON | ECHO | ECHOE | ISIG);
    /*Ignore parity errors*/
    opciones.c_iflag |= ~(INPCK | ISTRIP | PARMRK);
    opciones.c_iflag |= IGNPAR;
    opciones.c_iflag &= ~(IXON | IXOFF | IXANY | IGNCR | IGNBRK);
    opciones.c_iflag |= BRKINT;
    /*raw output
     * making the applycation ready to transmit*/
    opciones.c_oflag &= ~OPOST;
    /*aply*/
    tcsetattr(sd, TCSANOW, &opciones);

    pthread_t thread1;

    // initializing thread that scan serial port for incoming data.

     if( pthread_create( &thread1, NULL, read_serial, NULL ) != 0 )
     {
       printf("Cannot create thread1 , exiting \n ");
       exit(-1); // exit with errors
     }

		
    /**************************************************
     * Execute the xbee commands.
     **************************************************/

    write(sd,"+++",3);
    fprintf(stderr,"+++\n");
    sleep(2);

    int i=0;
    for(i=3;i<argc;i++)
    {
        if (strlen(argv[i])<512)
        {
            sprintf(order,"%s\r\n",argv[i]);
            write(sd,order,strlen(order));
            fprintf(stderr,"%s",order);
            usleep(50000);
        }
        else
        {
            fprintf(stderr,"Too long order to execute:%s\n",argv[i]);
        }
    }
    
    write(sd,"atcn\r\n",6);
    fprintf(stderr,"atcn\n");
    usleep(50000);
    end=1;

    // Any kind of trigger should be placed here.
    // In this while you can put any code you need in the main thread.
    usleep(500000); // This is just to free the CPU. Replace this with any other code, but remember to check CPU usage
    return 0;
} 
