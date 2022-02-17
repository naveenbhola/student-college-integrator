JAVACLASS = Porter.class TopicCategorizer.class
CPATH= -classpath $(CLASSPATH):.:
JCC=$(JC) $(CPATH)

all:$(JAVACLASS)
install: $(JAVACLASS)

Porter.class: Porter.java
	$(JCC) $(JFLAGS) Porter.java

TopicCategorizer.class: TopicCategorizer.java
	$(JCC) $(JFLAGS) TopicCategorizer.java

clean::
	rm -rf *.class core *.core *~ $(JAVACLASS)
include ../../Makefile.inc

